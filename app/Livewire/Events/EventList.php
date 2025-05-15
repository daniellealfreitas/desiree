<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class EventList extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'upcoming';
    public $dayFilter = 'all';

    public function render()
    {
        $query = Event::where('is_active', true);

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
            });
        }

        // Apply time filter
        if ($this->filter === 'upcoming') {
            $query->where('date', '>=', now()->format('Y-m-d'));
        } elseif ($this->filter === 'past') {
            $query->where('date', '<', now()->format('Y-m-d'));
        } elseif ($this->filter === 'this-week') {
            $query->whereBetween('date', [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ]);
        } elseif ($this->filter === 'this-month') {
            $query->whereMonth('date', now()->month)
                  ->whereYear('date', now()->year);
        }

        // Apply day of week filter
        if ($this->dayFilter === 'wednesday') {
            $query->whereRaw("strftime('%w', date) = '3'"); // 3 = Wednesday in SQLite (0-6, 0=Sunday)
        } elseif ($this->dayFilter === 'friday') {
            $query->whereRaw("strftime('%w', date) = '5'"); // 5 = Friday in SQLite
        } elseif ($this->dayFilter === 'saturday') {
            $query->whereRaw("strftime('%w', date) = '6'"); // 6 = Saturday in SQLite
        } elseif ($this->dayFilter === 'event-days') {
            $query->whereRaw("strftime('%w', date) IN ('3', '5', '6')"); // Wednesday, Friday, Saturday
        }

        // Order by date and time
        $query->orderBy('date')
              ->orderBy('start_time');

        $events = $query->paginate(6);

        // Get featured events
        $featuredEvents = Event::where('is_active', true)
            ->where('is_featured', true)
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->take(3)
            ->get();

        return view('livewire.events.event-list', [
            'events' => $events,
            'featuredEvents' => $featuredEvents
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatingDayFilter()
    {
        $this->resetPage();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function setDayFilter($dayFilter)
    {
        $this->dayFilter = $dayFilter;
    }
}
