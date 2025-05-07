<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Carbon\Carbon;

class EventCalendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $weeks = [];
    public $monthEvents = [];
    public $selectedDate = null;
    public $selectedDateEvents = [];
    
    public function mount()
    {
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->generateCalendar();
    }
    
    public function render()
    {
        return view('livewire.events.event-calendar');
    }
    
    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendar();
    }
    
    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendar();
    }
    
    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->loadEventsForDate($date);
    }
    
    private function generateCalendar()
    {
        $this->weeks = [];
        $this->monthEvents = [];
        
        // Get the first day of the month
        $firstDay = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        
        // Get the last day of the month
        $lastDay = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->endOfMonth();
        
        // Get the day of the week for the first day (0 = Sunday, 6 = Saturday)
        $firstDayOfWeek = $firstDay->dayOfWeek;
        
        // Adjust for Monday as the first day of the week
        $firstDayOfWeek = $firstDayOfWeek === 0 ? 6 : $firstDayOfWeek - 1;
        
        // Get the number of days in the month
        $daysInMonth = $lastDay->day;
        
        // Calculate the number of weeks in the month
        $weeksInMonth = ceil(($daysInMonth + $firstDayOfWeek) / 7);
        
        // Generate the calendar
        $day = 1;
        for ($week = 0; $week < $weeksInMonth; $week++) {
            $this->weeks[$week] = [];
            
            for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++) {
                if ($week === 0 && $dayOfWeek < $firstDayOfWeek) {
                    // Previous month days
                    $prevMonthDay = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)
                        ->subDays($firstDayOfWeek - $dayOfWeek);
                    
                    $this->weeks[$week][$dayOfWeek] = [
                        'day' => $prevMonthDay->day,
                        'month' => $prevMonthDay->month,
                        'year' => $prevMonthDay->year,
                        'isCurrentMonth' => false,
                        'date' => $prevMonthDay->format('Y-m-d'),
                        'isToday' => $prevMonthDay->isToday(),
                        'isWednesday' => $dayOfWeek === 2, // Wednesday
                        'isFriday' => $dayOfWeek === 4,     // Friday
                        'isSaturday' => $dayOfWeek === 5,   // Saturday
                    ];
                } elseif ($day > $daysInMonth) {
                    // Next month days
                    $nextMonthDay = Carbon::createFromDate($this->currentYear, $this->currentMonth, $daysInMonth)
                        ->addDays($day - $daysInMonth);
                    
                    $this->weeks[$week][$dayOfWeek] = [
                        'day' => $nextMonthDay->day,
                        'month' => $nextMonthDay->month,
                        'year' => $nextMonthDay->year,
                        'isCurrentMonth' => false,
                        'date' => $nextMonthDay->format('Y-m-d'),
                        'isToday' => $nextMonthDay->isToday(),
                        'isWednesday' => $dayOfWeek === 2, // Wednesday
                        'isFriday' => $dayOfWeek === 4,     // Friday
                        'isSaturday' => $dayOfWeek === 5,   // Saturday
                    ];
                } else {
                    // Current month days
                    $currentDate = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day);
                    
                    $this->weeks[$week][$dayOfWeek] = [
                        'day' => $day,
                        'month' => $this->currentMonth,
                        'year' => $this->currentYear,
                        'isCurrentMonth' => true,
                        'date' => $currentDate->format('Y-m-d'),
                        'isToday' => $currentDate->isToday(),
                        'isWednesday' => $dayOfWeek === 2, // Wednesday
                        'isFriday' => $dayOfWeek === 4,     // Friday
                        'isSaturday' => $dayOfWeek === 5,   // Saturday
                    ];
                    
                    $day++;
                }
            }
        }
        
        // Load events for the current month
        $this->loadEventsForMonth();
        
        // If a date was previously selected, try to select it again if it's in the current month
        if ($this->selectedDate) {
            $selectedDateCarbon = Carbon::parse($this->selectedDate);
            if ($selectedDateCarbon->month == $this->currentMonth && $selectedDateCarbon->year == $this->currentYear) {
                $this->loadEventsForDate($this->selectedDate);
            } else {
                $this->selectedDate = null;
                $this->selectedDateEvents = [];
            }
        }
    }
    
    private function loadEventsForMonth()
    {
        $startDate = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->format('Y-m-d');
        $endDate = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->endOfMonth()->format('Y-m-d');
        
        $events = Event::where('is_active', true)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        $this->monthEvents = [];
        
        foreach ($events as $event) {
            $date = $event->date->format('Y-m-d');
            
            if (!isset($this->monthEvents[$date])) {
                $this->monthEvents[$date] = [];
            }
            
            $this->monthEvents[$date][] = $event;
        }
    }
    
    private function loadEventsForDate($date)
    {
        $this->selectedDateEvents = Event::where('is_active', true)
            ->whereDate('date', $date)
            ->orderBy('start_time')
            ->get();
    }
}
