<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FollowRequest;
use App\Models\Notification;

class FollowRequestManager extends Component
{
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
        
        $this->dispatch('request-handled');
    }

    public function reject($requestId)
    {
        $request = FollowRequest::findOrFail($requestId);
        $request->update(['status' => 'rejected']);
        $this->dispatch('request-handled');
    }

    public function render()
    {
        return view('livewire.follow-request-manager', [
            'requests' => FollowRequest::where('receiver_id', auth()->id())
                ->where('status', 'pending')
                ->with('sender.userPhotos')
                ->latest()
                ->get()
        ]);
    }
}
