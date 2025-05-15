<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FollowRequest;
use App\Models\Notification;
use Carbon\Carbon;

class FollowRequestNotifications extends Component
{
    public $followRequests = [];

    public function mount()
    {
        $this->loadRequests();
    }

    public function loadRequests()
    {
        $this->followRequests = FollowRequest::where('receiver_id', auth()->id())
            ->where('status', 'pending')
            ->with(['sender.userPhotos' => function($query) {
                $query->latest()->take(1);
            }])
            ->latest()
            ->get();
    }

    public function hasRequests()
    {
        return $this->followRequests->count() > 0;
    }

    public function accept($requestId)
    {
        try {
            $request = FollowRequest::findOrFail($requestId);

            // Check if relationship already exists
            if (!auth()->user()->followers()->where('follower_id', $request->sender_id)->exists()) {
                auth()->user()->followers()->attach($request->sender_id);
            }

            $request->update(['status' => 'accepted']);

            // Avoid duplicate notifications
            if (!Notification::where('user_id', $request->sender_id)
                              ->where('sender_id', auth()->id())
                              ->where('type', 'follow_accepted')
                              ->exists()) {
                Notification::create([
                    'user_id' => $request->sender_id,
                    'sender_id' => auth()->id(),
                    'type' => 'follow_accepted',
                    'message' => auth()->user()->username . ' aceitou sua solicitação para seguir'
                ]);
            }

            $this->dispatch('notification-received');
            $this->loadRequests();
        } catch (\Exception $e) {
            logger()->error('Follow accept error: ' . $e->getMessage());
        }
    }

    public function reject($requestId)
    {
        $request = FollowRequest::findOrFail($requestId);
        $request->update(['status' => 'rejected']);

        // Avoid duplicate notifications
        if (!Notification::where('user_id', $request->sender_id)
                          ->where('sender_id', auth()->id())
                          ->where('type', 'follow_rejected')
                          ->exists()) {
            Notification::create([
                'user_id' => $request->sender_id,
                'sender_id' => auth()->id(),
                'type' => 'follow_rejected',
                'message' => auth()->user()->username . ' rejeitou sua solicitação para seguir'
            ]);
        }

        $this->dispatch('notification-received');
        $this->loadRequests();
    }

    public function render()
    {
        return view('livewire.follow-request-notifications');
    }
}
