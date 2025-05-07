<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserOnlineStat;
use Carbon\Carbon;

class UserStatusIndicator extends Component
{
    public $userId;
    public $status;
    public $lastSeen;
    public $showDetails = false;

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->refreshStatus();
    }

    public function refreshStatus()
    {
        $user = User::find($this->userId);

        if ($user) {
            $this->status = $user->presence_status;
            $this->lastSeen = $user->last_seen;
        } else {
            $this->status = 'offline';
            $this->lastSeen = null;
        }
    }

    public function setAwayStatus()
    {
        // Apenas o próprio usuário pode alterar seu status
        if (auth()->id() == $this->userId) {
            $user = User::find($this->userId);
            if ($user && $user->status !== 'away') {
                $oldStatus = $user->status;
                $user->status = 'away';
                $user->save();
                $this->status = 'away';

                // Registrar a mudança de status para estatísticas (se a classe existir)
                try {
                    UserOnlineStat::updateOnStatusChange($this->userId, 'away');
                } catch (\Exception $e) {
                    // Silenciosamente ignora erros relacionados à tabela de estatísticas
                }
            }
        }
    }

    public function setOnlineStatus()
    {
        // Apenas o próprio usuário pode alterar seu status
        if (auth()->id() == $this->userId) {
            $user = User::find($this->userId);
            if ($user && $user->status !== 'online') {
                $oldStatus = $user->status;
                $user->status = 'online';
                $user->last_seen = now();
                $user->save();
                $this->status = 'online';

                // Registrar a mudança de status para estatísticas
                UserOnlineStat::updateOnStatusChange($this->userId, 'online');
            }
        }
    }

    public function setDndStatus()
    {
        // Apenas o próprio usuário pode alterar seu status
        if (auth()->id() == $this->userId) {
            $user = User::find($this->userId);
            if ($user && $user->status !== 'dnd') {
                $oldStatus = $user->status;
                $user->status = 'dnd';
                $user->save();
                $this->status = 'dnd';

                // Registrar a mudança de status para estatísticas
                UserOnlineStat::updateOnStatusChange($this->userId, 'dnd');
            }
        }
    }

    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }

    // Propriedade computada no Livewire 3
    public function getFormattedLastSeenProperty()
    {
        if (!$this->lastSeen) {
            return 'Nunca';
        }

        $lastSeen = Carbon::parse($this->lastSeen);

        if ($lastSeen->diffInMinutes(now()) < 60) {
            return $lastSeen->diffForHumans();
        } elseif ($lastSeen->isToday()) {
            return 'Hoje às ' . $lastSeen->format('H:i');
        } elseif ($lastSeen->isYesterday()) {
            return 'Ontem às ' . $lastSeen->format('H:i');
        } else {
            return $lastSeen->format('d/m/Y H:i');
        }
    }

    public function render()
    {
        return view('livewire.user-status-indicator');
    }
}