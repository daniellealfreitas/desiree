<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventAttendee;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventManager extends Component
{
    use WithPagination, WithFileUploads;

    // Form properties
    public $name;
    public $description;
    public $date;
    public $start_time;
    public $end_time;
    public $image;
    public $cover_image;
    public $price;
    public $capacity;

    public $is_featured = false;
    public $is_active = true;

    // Temporary image URLs for preview
    public $temp_image;
    public $temp_cover_image;

    // Edit mode
    public $editMode = false;
    public $eventId;

    // Modals
    public $showDeleteModal = false;
    public $showEventModal = false;
    public $showAttendeeModal = false;

    // Filters
    public $search = '';
    public $status = '';
    public $dateFilter = '';

    // Attendees
    public $selectedEvent;
    public $attendees = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'start_time' => 'required|string',
        'end_time' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
        'cover_image' => 'nullable|image|max:2048',
        'price' => 'nullable|numeric|min:0',
        'capacity' => 'nullable|integer|min:1',

        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'O nome do evento é obrigatório.',
        'date.required' => 'A data do evento é obrigatória.',
        'start_time.required' => 'A hora de início é obrigatória.',
        'image.image' => 'O arquivo deve ser uma imagem válida.',
        'image.max' => 'A imagem não pode ter mais de 2MB.',
        'cover_image.image' => 'O arquivo deve ser uma imagem válida.',
        'cover_image.max' => 'A imagem de capa não pode ter mais de 2MB.',
        'price.numeric' => 'O preço deve ser um valor numérico.',
        'price.min' => 'O preço não pode ser negativo.',
        'capacity.integer' => 'A capacidade deve ser um número inteiro.',
        'capacity.min' => 'A capacidade deve ser pelo menos 1.',
    ];

    public function mount()
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('events.index');
        }
    }

    public function render()
    {
        $query = Event::query();

        // Apply search filter
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
        }

        // Apply status filter
        if ($this->status === 'active') {
            $query->where('is_active', true);
        } elseif ($this->status === 'inactive') {
            $query->where('is_active', false);
        }

        // Apply date filter
        if ($this->dateFilter === 'upcoming') {
            $query->where('date', '>=', now()->format('Y-m-d'));
        } elseif ($this->dateFilter === 'past') {
            $query->where('date', '<', now()->format('Y-m-d'));
        } elseif ($this->dateFilter === 'today') {
            $query->whereDate('date', now()->format('Y-m-d'));
        } elseif ($this->dateFilter === 'this-week') {
            $query->whereBetween('date', [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ]);
        } elseif ($this->dateFilter === 'this-month') {
            $query->whereMonth('date', now()->month)
                  ->whereYear('date', now()->year);
        }

        $events = $query->orderBy('date', 'desc')->paginate(10);

        return view('livewire.admin.event-manager', [
            'events' => $events
        ]);
    }

    public function createEvent()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showEventModal = true;
    }

    public function editEvent($id)
    {
        $this->resetForm();
        $this->editMode = true;
        $this->eventId = $id;

        $event = Event::findOrFail($id);

        $this->name = $event->name;
        $this->description = $event->description;
        $this->date = $event->date->format('Y-m-d');
        $this->start_time = $event->start_time->format('H:i');
        $this->end_time = $event->end_time ? $event->end_time->format('H:i') : null;
        $this->temp_image = $event->image ? Storage::url($event->image) : null;
        $this->temp_cover_image = $event->cover_image ? Storage::url($event->cover_image) : null;
        $this->price = $event->price;
        $this->capacity = $event->capacity;

        $this->is_featured = $event->is_featured;
        $this->is_active = $event->is_active;

        $this->showEventModal = true;
    }

    public function saveEvent()
    {
        try {
            // Validate form data
            $validatedData = $this->validate();

            if ($this->editMode) {
                $event = Event::findOrFail($this->eventId);

                // Update event data
                $event->name = $this->name;
                $event->description = $this->description;
                $event->date = $this->date;
                $event->start_time = $this->start_time;
                $event->end_time = $this->end_time;
                $event->price = $this->price ?? 0;
                $event->capacity = $this->capacity;

                $event->is_featured = $this->is_featured;
                $event->is_active = $this->is_active;

                // Handle image uploads
                if ($this->image) {
                    // Delete old image if exists
                    if ($event->image) {
                        Storage::disk('public')->delete($event->image);
                    }

                    $event->image = $this->image->store('events/images', 'public');
                }

                if ($this->cover_image) {
                    // Delete old cover image if exists
                    if ($event->cover_image) {
                        Storage::disk('public')->delete($event->cover_image);
                    }

                    $event->cover_image = $this->cover_image->store('events/covers', 'public');
                }

                $event->save();

                session()->flash('success', 'Evento atualizado com sucesso!');
            } else {
                // Create new event
                $event = new Event();
                $event->name = $this->name;
                $event->slug = Str::slug($this->name);
                $event->description = $this->description;
                $event->date = $this->date;
                $event->start_time = $this->start_time;
                $event->end_time = $this->end_time;
                $event->price = $this->price ?? 0;
                $event->capacity = $this->capacity;

                $event->is_featured = $this->is_featured;
                $event->is_active = $this->is_active;
                $event->created_by = Auth::id();

                // Handle image uploads
                if ($this->image) {
                    $event->image = $this->image->store('events/images', 'public');
                }

                if ($this->cover_image) {
                    $event->cover_image = $this->cover_image->store('events/covers', 'public');
                }

                $event->save();

                session()->flash('success', 'Evento criado com sucesso!');
            }

            $this->resetForm();
            $this->showEventModal = false;
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Erro ao salvar evento: ' . $e->getMessage());

            // Show error message to user
            session()->flash('error', 'Erro ao salvar evento: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->eventId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteEvent()
    {
        $event = Event::findOrFail($this->eventId);

        // Delete event images
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        session()->flash('success', 'Evento excluído com sucesso!');
        $this->showDeleteModal = false;
    }

    public function viewAttendees($id)
    {
        $this->selectedEvent = Event::findOrFail($id);
        $this->attendees = EventAttendee::where('event_id', $id)
            ->with(['user' => function($query) {
                $query->with('userPhotos');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->showAttendeeModal = true;
    }

    public function checkInAttendee($attendeeId)
    {
        $attendee = EventAttendee::findOrFail($attendeeId);

        if ($attendee->status !== 'confirmed') {
            session()->flash('error', 'Este participante não está confirmado.');
            return;
        }

        $attendee->checkIn();

        // Refresh attendees list
        $this->attendees = EventAttendee::where('event_id', $this->selectedEvent->id)
            ->with(['user' => function($query) {
                $query->with('userPhotos');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        session()->flash('success', 'Check-in realizado com sucesso!');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->date = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->image = null;
        $this->cover_image = null;
        $this->temp_image = null;
        $this->temp_cover_image = null;
        $this->price = '';
        $this->capacity = '';

        $this->is_featured = false;
        $this->is_active = true;
        $this->eventId = null;

        $this->resetValidation();
    }
}
