<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class EventAttendeeController extends Controller
{
    /**
     * Register the user for the event.
     */
    public function register(Request $request, Event $event)
    {
        // Check if the event is active
        if (!$event->is_active) {
            return redirect()->route('events.show', $event->slug)
                ->with('error', 'Este evento não está mais disponível para inscrições.');
        }
        
        // Check if the event is in the past
        if ($event->has_passed) {
            return redirect()->route('events.show', $event->slug)
                ->with('error', 'Este evento já ocorreu.');
        }
        
        // Check if the event is sold out
        if ($event->is_sold_out) {
            return redirect()->route('events.show', $event->slug)
                ->with('error', 'Este evento está esgotado.');
        }
        
        // Check if the user is already registered
        $existingRegistration = EventAttendee::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->first();
            
        if ($existingRegistration) {
            return redirect()->route('events.show', $event->slug)
                ->with('info', 'Você já está inscrito neste evento.');
        }
        
        // If the event is free, register the user directly
        if ($event->is_free) {
            $attendee = EventAttendee::create([
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'status' => 'confirmed',
                'ticket_code' => EventAttendee::generateTicketCode(),
                'payment_status' => 'completed',
                'payment_method' => 'free',
                'amount_paid' => 0,
                'paid_at' => now(),
            ]);
            
            return redirect()->route('events.show', $event->slug)
                ->with('success', 'Inscrição realizada com sucesso!');
        }
        
        // If the event has a price, create a registration and redirect to payment
        $attendee = EventAttendee::create([
            'event_id' => $event->id,
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
                            'name' => $event->name,
                            'description' => "Ingresso para {$event->name} em {$event->formatted_date}",
                            'images' => [$event->image_url],
                        ],
                        'unit_amount' => (int)($event->price * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('events.payment.success', ['event' => $event->id, 'attendee' => $attendee->id]),
                'cancel_url' => route('events.payment.cancel', ['event' => $event->id, 'attendee' => $attendee->id]),
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'event_id' => $event->id,
                    'attendee_id' => $attendee->id,
                    'user_id' => Auth::id(),
                ],
            ]);
            
            return redirect($session->url);
            
        } catch (ApiErrorException $e) {
            // Delete the registration if payment creation fails
            $attendee->delete();
            
            return redirect()->route('events.show', $event->slug)
                ->with('error', 'Ocorreu um erro ao processar o pagamento. Por favor, tente novamente.');
        }
    }
    
    /**
     * Handle successful payment.
     */
    public function paymentSuccess(Request $request, Event $event, EventAttendee $attendee)
    {
        // Verify that the attendee belongs to the event and the current user
        if ($attendee->event_id != $event->id || $attendee->user_id != Auth::id()) {
            return redirect()->route('events.show', $event->slug)
                ->with('error', 'Registro de inscrição inválido.');
        }
        
        // Mark the payment as completed
        $attendee->markPaymentCompleted(
            $request->session_id ?? 'manual',
            'stripe',
            $event->price
        );
        
        return redirect()->route('events.show', $event->slug)
            ->with('success', 'Pagamento realizado com sucesso! Seu ingresso foi confirmado.');
    }
    
    /**
     * Handle cancelled payment.
     */
    public function paymentCancel(Request $request, Event $event, EventAttendee $attendee)
    {
        // Verify that the attendee belongs to the event and the current user
        if ($attendee->event_id != $event->id || $attendee->user_id != Auth::id()) {
            return redirect()->route('events.show', $event->slug)
                ->with('error', 'Registro de inscrição inválido.');
        }
        
        // Mark the payment as failed
        $attendee->markPaymentFailed();
        
        return redirect()->route('events.show', $event->slug)
            ->with('info', 'Pagamento cancelado. Você pode tentar novamente a qualquer momento.');
    }
    
    /**
     * Cancel the user's registration for the event.
     */
    public function cancel(Event $event)
    {
        $attendee = EventAttendee::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->firstOrFail();
            
        $attendee->cancel();
        
        return redirect()->route('events.show', $event->slug)
            ->with('success', 'Sua inscrição foi cancelada.');
    }
    
    /**
     * Check in an attendee (admin only).
     */
    public function checkIn(Request $request, Event $event, EventAttendee $attendee)
    {
        // Only admins can check in attendees
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('events.show', $event->slug)
                ->with('error', 'Você não tem permissão para fazer check-in de participantes.');
        }
        
        // Verify that the attendee belongs to the event
        if ($attendee->event_id != $event->id) {
            return redirect()->route('events.show', $event->slug)
                ->with('error', 'Registro de inscrição inválido.');
        }
        
        // Check if the attendee is confirmed
        if ($attendee->status != 'confirmed') {
            return redirect()->route('events.show', $event->slug)
                ->with('error', 'Este participante não está confirmado.');
        }
        
        $attendee->checkIn();
        
        return redirect()->route('events.show', $event->slug)
            ->with('success', 'Check-in realizado com sucesso para ' . $attendee->user->name);
    }
}
