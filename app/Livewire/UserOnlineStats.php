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
        // Obter estatísticas atualizadas para hoje
        return $this->user->today_online_stats;
    }

    public function getWeekStats()
    {
        // Atualizar as estatísticas de hoje antes de obter as da semana
        $this->getTodayStats();

        $stats = $this->user->current_week_online_stats;
        return $this->aggregateStats($stats);
    }

    public function getMonthStats()
    {
        // Atualizar as estatísticas de hoje antes de obter as do mês
        $this->getTodayStats();

        $stats = $this->user->current_month_online_stats;
        return $this->aggregateStats($stats);
    }

    protected function aggregateStats(Collection $stats)
    {
        $minutesOnline = max(0, $stats->sum('minutes_online'));
        $minutesAway = max(0, $stats->sum('minutes_away'));
        $minutesDnd = max(0, $stats->sum('minutes_dnd'));

        return [
            'minutes_online' => $minutesOnline,
            'minutes_away' => $minutesAway,
            'minutes_dnd' => $minutesDnd,
            'total_minutes' => $minutesOnline + $minutesAway + $minutesDnd,
            'days_active' => $stats->count(),
        ];
    }

    public function formatMinutes($minutes)
    {
        // Garantir que estamos trabalhando com um número inteiro positivo
        $minutes = abs((int) $minutes);

        if ($minutes < 60) {
            return $minutes . ' min';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return $hours . 'h';
        }

        // Formatar minutos com zero à esquerda se for menor que 10
        $formattedMinutes = str_pad($remainingMinutes, 2, '0', STR_PAD_LEFT);

        return $hours . 'h ' . $formattedMinutes . 'min';
    }

    public function render()
    {
        // Atualizar estatísticas do usuário atual se for o usuário autenticado
        if (auth()->check() && auth()->id() === $this->user->id) {
            // Obter o status atual do usuário
            $currentStatus = $this->user->presence_status;

            // Atualizar as estatísticas com o status atual
            if ($currentStatus !== 'offline') {
                \App\Models\UserOnlineStat::updateOnStatusChange($this->user->id, $currentStatus);
            }
        }

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
