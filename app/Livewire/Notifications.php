<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;

class Notifications extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        // Busca apenas notificações não lidas
        $this->notifications = auth()->user()->notifications()
            ->where('read', false)
            ->with(['sender', 'post'])
            ->latest()
            ->take(5)
            ->get();

        // Conta quantas não lidas existem
        $this->unreadCount = $this->notifications->count();
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->where('id', $notificationId)->first();
        if ($notification && !$notification->read) {
            $notification->update(['read' => true]);
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
