<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserPointLog;
use App\Models\UserPoint;
use Illuminate\Support\Facades\Auth;

class UserPointsHistory extends Component
{
    use WithPagination;

    public $userId;
    public $period = 'all';
    public $actionType = 'all';
    public $perPage = 10;

    public function mount($userId = null)
    {
        $this->userId = $userId ?? Auth::id();
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        $this->resetPage();
    }

    public function setActionType($actionType)
    {
        $this->actionType = $actionType;
        $this->resetPage();
    }

    public function render()
    {
        $query = UserPointLog::where('user_id', $this->userId)
            ->orderBy('created_at', 'desc');

        // Filtrar por período
        if ($this->period !== 'all') {
            $query->where('created_at', '>=', now()->sub($this->period));
        }

        // Filtrar por tipo de ação
        if ($this->actionType !== 'all') {
            $query->where('action_type', $this->actionType);
        }

        $logs = $query->paginate($this->perPage);

        // Obter estatísticas
        $userPoint = UserPoint::where('user_id', $this->userId)->first();
        $totalPoints = $userPoint ? $userPoint->total_points : 0;
        $dailyPoints = $userPoint ? $userPoint->daily_points : 0;
        $weeklyPoints = $userPoint ? $userPoint->weekly_points : 0;
        $monthlyPoints = $userPoint ? $userPoint->monthly_points : 0;
        $streakDays = $userPoint ? $userPoint->streak_days : 0;

        // Obter posição no ranking
        $rankingPosition = \App\Models\User::where('ranking_points', '>', Auth::user()->ranking_points)->count() + 1;

        // Obter tipos de ações disponíveis para o filtro
        $actionTypes = UserPointLog::where('user_id', $this->userId)
            ->select('action_type')
            ->distinct()
            ->pluck('action_type')
            ->toArray();

        return view('livewire.user-points-history', [
            'logs' => $logs,
            'totalPoints' => $totalPoints,
            'dailyPoints' => $dailyPoints,
            'weeklyPoints' => $weeklyPoints,
            'monthlyPoints' => $monthlyPoints,
            'streakDays' => $streakDays,
            'rankingPosition' => $rankingPosition,
            'actionTypes' => $actionTypes,
        ]);
    }
}
