<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserOnlineStat;
use Illuminate\Support\Facades\Auth;

class UserStatusManager extends Component
{
    public User $user;
    public $userStatus;
    public $statusOptions = [
        'online' => 'Online',
        'away'   => 'Ausente',
        'dnd'    => 'Não Perturbe',
        'offline'=> 'Offline'
    ];
    public $isEditing = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->userStatus = $user->status;
    }

    public function refreshStatus()
    {
        // Recarregar usuário para obter o status mais recente
        $this->user->refresh();
        $this->userStatus = $this->user->status;

        // Se o usuário autenticado for o proprietário do perfil, atualizar last_seen
        if (Auth::id() === $this->user->id) {
            $this->user->update([
                'last_seen' => now(),
            ]);
        }
    }

    public function updateStatus()
    {
        // Apenas o próprio usuário pode alterar seu status
        if (Auth::id() === $this->user->id) {
            $oldStatus = $this->user->status;
            $this->user->status = $this->userStatus;

            if ($this->userStatus === 'online') {
                $this->user->last_seen = now();
            }

            $this->user->save();
            $this->isEditing = false;

            // Registrar a mudança de status para estatísticas
            if ($oldStatus !== $this->userStatus) {
                UserOnlineStat::updateOnStatusChange($this->user->id, $this->userStatus);
            }
        }
    }

    public function toggleEditMode()
    {
        // Apenas o próprio usuário pode editar seu status
        if (Auth::id() === $this->user->id) {
            $this->isEditing = !$this->isEditing;
        }
    }

    public function render()
    {
        return view('livewire.user-status-manager', [
            'effectiveStatus' => $this->user->presence_status,
        ]);
    }
}
