<?php

use function Livewire\Volt\state;
use function Livewire\Volt\mount;
use App\Models\User;
use App\Models\FollowRequest;
use Illuminate\Support\Facades\Auth;

state(['recentUsers' => []]);
state(['requestStatus' => []]);

$getRecentUsers = function () {
    $this->recentUsers = User::with(['userPhotos' => function($query) {
        $query->latest()->take(1);
    }])
    ->select('id', 'name', 'username', 'last_login_at') // Added 'username'
    ->orderBy('last_login_at', 'desc')
    ->get()
    ->map(function ($user) {
        // Ensure 'username' is included in the user data
        if (!isset($user->username)) {
            $user->username = User::find($user->id)->username;
        }
        // Check for existing follow request
        $request = FollowRequest::where('sender_id', Auth::id())
            ->where('receiver_id', $user->id)
            ->first();
            
        $this->requestStatus[$user->id] = $request ? $request->status : null;
        return $user;
    })
    ->toArray();
};

$toggleFollow = function ($userId) {
    $existingRequest = FollowRequest::where('sender_id', Auth::id())
        ->where('receiver_id', $userId)
        ->first();

    if ($existingRequest) {
        $existingRequest->delete();
        $this->requestStatus[$userId] = null;
    } else {
        FollowRequest::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'status' => 'pending'
        ]);
        $this->requestStatus[$userId] = 'pending';
    }
};

mount(function () {
    $this->getRecentUsers();
});

?>

<div id="ultimos_acessos">
    <h3 class="text-white bg-gray-800 p-3 rounded-t-lg font-semibold">Últimos Acessos</h3>
    <ul class="p-3 space-y-2">
        @foreach ($recentUsers as $user)
            <li class="flex items-center justify-between space-x-3">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset($user['user_photos'][0]['photo_path'] ?? 'images/default-avatar.jpg') }}" class="w-10 h-10 rounded-full object-cover">
                    <span>
                        <a href="/{{ $user['username'] }}" class="text-blue-500 hover:underline">
                            {{ $user['name'] }}
                        </a>
                    </span>
                </div>
                @if($user['id'] !== Auth::id())
                    <button wire:click="toggleFollow({{ $user['id'] }})" 
                            @class([
                                'px-4 py-2 rounded text-sm font-medium',
                                'bg-yellow-500 text-white' => $requestStatus[$user['id']] === 'pending',
                                'bg-gray-200 text-gray-800 cursor-not-allowed' => $requestStatus[$user['id']] === 'accepted',
                                'bg-blue-500 text-white hover:bg-blue-600' => !$requestStatus[$user['id']]
                            ])>
                        @if($requestStatus[$user['id']] === 'pending')
                            {{ __('Solicitado') }}
                        @elseif($requestStatus[$user['id']] === 'accepted')
                            {{ __('Seguindo') }}
                        @else
                            {{ __('Seguir') }}
                        @endif
                    </button>
                @endif
            </li>
        @endforeach
    </ul>
</div>
