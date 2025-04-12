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
        $this->notifications = auth()->user()->notifications()
            ->with(['sender', 'post'])
            ->latest()
            ->take(5)
            ->get();
        
        $this->unreadCount = auth()->user()->notifications()
            ->where('read', false)
            ->count();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->update(['read' => true]);
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
