<?php

namespace App\Livewire\Events;

use App\Models\Event;
use App\Models\EventAttendee;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class EventRegistration extends Component
{
    public Event $event;
    public $isRegistered = false;
    public $attendee = null;
    public $showConfirmModal = false;
    public $showCancelModal = false;
    
    public function mount(Event $event)
    {
        $this->event = $event;
        $this->checkRegistration();
    }
    
    public function render()
    {
        return view('livewire.events.event-registration');
    }
    
    public function checkRegistration()
    {
        if (Auth::check()) {
            $attendee = EventAttendee::where('event_id', $this->event->id)
                ->where('user_id', Auth::id())
                ->where('status', '!=', 'cancelled')
                ->first();
                
            $this->isRegistered = (bool) $attendee;
            $this->attendee = $attendee;
        } else {
            $this->isRegistered = false;
            $this->attendee = null;
        }
    }
    
    public function confirmRegistration()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if the event is active
        if (!$this->event->is_active) {
            session()->flash('error', 'Este evento não está mais disponível para inscrições.');
            return;
        }
        
        // Check if the event is in the past
        if ($this->event->has_passed) {
            session()->flash('error', 'Este evento já ocorreu.');
            return;
        }
        
        // Check if the event is sold out
        if ($this->event->is_sold_out) {
            session()->flash('error', 'Este evento está esgotado.');
            return;
        }
        
        // Check if the user is already registered
        if ($this->isRegistered) {
            session()->flash('info', 'Você já está inscrito neste evento.');
            return;
        }
        
        $this->showConfirmModal = true;
    }
    
    public function register()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if the event is active
        if (!$this->event->is_active) {
            session()->flash('error', 'Este evento não está mais disponível para inscrições.');
            return;
        }
        
        // Check if the event is in the past
        if ($this->event->has_passed) {
            session()->flash('error', 'Este evento já ocorreu.');
            return;
        }
        
        // Check if the event is sold out
        if ($this->event->is_sold_out) {
            session()->flash('error', 'Este evento está esgotado.');
            return;
        }
        
        // Check if the user is already registered
        $existingRegistration = EventAttendee::where('event_id', $this->event->id)
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->first();
            
        if ($existingRegistration) {
            session()->flash('info', 'Você já está inscrito neste evento.');
            $this->showConfirmModal = false;
            return;
        }
        
        // If the event is free, register the user directly
        if ($this->event->is_free) {
            $attendee = EventAttendee::create([
                'event_id' => $this->event->id,
                'user_id' => Auth::id(),
                'status' => 'confirmed',
                'ticket_code' => EventAttendee::generateTicketCode(),
                'payment_status' => 'completed',
                'payment_method' => 'free',
                'amount_paid' => 0,
                'paid_at' => now(),
            ]);
            
            $this->attendee = $attendee;
            $this->isRegistered = true;
            $this->showConfirmModal = false;
            
            session()->flash('success', 'Inscrição realizada com sucesso!');
            return;
        }
        
        // If the event has a price, create a registration and redirect to payment
        $attendee = EventAttendee::create([
            'event_id' => $this->event->id,
            'user_id' => Auth::id(),
            'status' => 'registered',
            'payment_status' => 'pending',
        ]);
        
        // Create Stripe checkout session
        try {
            Stripe::setApiKey(config('cashier.secret'));
            
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'brl',
                        'product_data' => [
                            'name' => $this->event->name,
                            'description' => "Ingresso para {$this->event->name} em {$this->event->formatted_date}",
                            'images' => [$this->event->image_url],
                        ],
                        'unit_amount' => (int)($this->event->price * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('events.payment.success', ['event' => $this->event->id, 'attendee' => $attendee->id]),
                'cancel_url' => route('events.payment.cancel', ['event' => $this->event->id, 'attendee' => $attendee->id]),
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'event_id' => $this->event->id,
                    'attendee_id' => $attendee->id,
                    'user_id' => Auth::id(),
                ],
            ]);
            
            $this->showConfirmModal = false;
            return redirect($session->url);
            
        } catch (ApiErrorException $e) {
            // Delete the registration if payment creation fails
            $attendee->delete();
            
            session()->flash('error', 'Ocorreu um erro ao processar o pagamento. Por favor, tente novamente.');
            $this->showConfirmModal = false;
        }
    }
    
    public function confirmCancel()
    {
        if (!$this->isRegistered || !$this->attendee) {
            session()->flash('error', 'Você não está inscrito neste evento.');
            return;
        }
        
        $this->showCancelModal = true;
    }
    
    public function cancelRegistration()
    {
        if (!$this->isRegistered || !$this->attendee) {
            session()->flash('error', 'Você não está inscrito neste evento.');
            return;
        }
        
        $this->attendee->cancel();
        
        $this->isRegistered = false;
        $this->attendee = null;
        $this->showCancelModal = false;
        
        session()->flash('success', 'Sua inscrição foi cancelada.');
    }
}
