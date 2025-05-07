<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserOnlineStat;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UserOnlineStats extends Component
{
    public User $user;
    public $period = 'week'; // 'day', 'week', 'month'
    
    public function mount(User $user)
    {
        $this->user = $user;
    }
    
    public function setPeriod($period)
    {
        if (in_array($period, ['day', 'week', 'month'])) {
            $this->period = $period;
        }
    }
    
    public function getTodayStats()
    {
        return $this->user->today_online_stats;
    }
    
    public function getWeekStats()
    {
        $stats = $this->user->current_week_online_stats;
        return $this->aggregateStats($stats);
    }
    
    public function getMonthStats()
    {
        $stats = $this->user->current_month_online_stats;
        return $this->aggregateStats($stats);
    }
    
    protected function aggregateStats(Collection $stats)
    {
        return [
            'minutes_online' => $stats->sum('minutes_online'),
            'minutes_away' => $stats->sum('minutes_away'),
            'minutes_dnd' => $stats->sum('minutes_dnd'),
            'total_minutes' => $stats->sum('minutes_online') + $stats->sum('minutes_away') + $stats->sum('minutes_dnd'),
            'days_active' => $stats->count(),
        ];
    }
    
    public function formatMinutes($minutes)
    {
        if ($minutes < 60) {
            return $minutes . ' min';
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($remainingMinutes === 0) {
            return $hours . 'h';
        }
        
        return $hours . 'h ' . $remainingMinutes . 'min';
    }
    
    public function render()
    {
        $stats = null;
        
        switch ($this->period) {
            case 'day':
                $stats = $this->getTodayStats();
                break;
            case 'week':
                $stats = $this->getWeekStats();
                break;
            case 'month':
                $stats = $this->getMonthStats();
                break;
        }
        
        return view('livewire.user-online-stats', [
            'stats' => $stats,
        ]);
    }
}
