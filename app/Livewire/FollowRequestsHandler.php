<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FollowRequest;
use App\Models\Notification;

class FollowRequestsHandler extends Component
{
    public function sendRequest($userId)
    {
        $request = FollowRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $userId,
            'status' => 'pending'
        ]);

        Notification::create([
            'user_id' => $userId,
            'sender_id' => auth()->id(),
            'type' => 'follow_request',
        ]);
    }

    public function acceptRequest($requestId)
    {
        $request = FollowRequest::findOrFail($requestId);
        $request->update(['status' => 'accepted']);
        auth()->user()->followers()->attach($request->sender_id);

        Notification::create([
            'user_id' => $request->sender_id,
            'sender_id' => auth()->id(),
            'type' => 'follow_accepted',
        ]);
    }

    public function rejectRequest($requestId)
    {
        $request = FollowRequest::findOrFail($requestId);
        $request->update(['status' => 'rejected']);
    }

    public function render()
    {
        return view('livewire.follow-requests-handler', [
            'pendingRequests' => auth()->user()->followRequests()
                ->where('status', 'pending')
                ->get()
        ]);
    }
}
