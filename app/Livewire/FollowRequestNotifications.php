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
        $this->loadFollowRequests();
    }

    public function loadFollowRequests()
    {
        $this->followRequests = FollowRequest::where('receiver_id', auth()->id())
            ->where('status', 'pending')
            ->with(['sender.userPhotos' => function($query) {
                $query->latest()->take(1);
            }])
            ->get();
    }

    public function accept($requestId)
    {
        $request = FollowRequest::findOrFail($requestId);
        $request->update(['status' => 'accepted']);
        auth()->user()->followers()->attach($request->sender_id);
        
        Notification::create([
            'user_id' => $request->sender_id,
            'sender_id' => auth()->id(),
            'type' => 'follow_accepted'
        ]);

        $this->loadFollowRequests();
    }

    public function reject($requestId)
    {
        FollowRequest::findOrFail($requestId)->update(['status' => 'rejected']);
        $this->loadFollowRequests();
    }

    public function render()
    {
        return view('livewire.follow-request-notifications');
    }
}
