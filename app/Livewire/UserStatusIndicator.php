<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserStatusIndicator extends Component
{
    public $userId;
    public $status;

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->refreshStatus();
    }

    public function refreshStatus()
    {
        $user = User::find($this->userId);
        $this->status = $user ? $user->presence_status : 'offline';
    }

    public function render()
    {
        return view('livewire.user-status-indicator');
    }
}