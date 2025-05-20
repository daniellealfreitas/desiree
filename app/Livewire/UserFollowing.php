<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\UserPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class UserFollowing extends Component
{
    public $userId;
    public $following = [];
    public $showModal = false;
    public $followStatus = [];

    // Livewire 3 usa o método getListeners() em vez de $listeners
    protected function getListeners()
    {
        return ['show-user-following' => 'loadFollowing'];
    }

    public function loadFollowing($userId)
    {
        $this->userId = $userId;
        $this->showModal = true;

        $user = User::find($this->userId);
        $followingUsers = $user->following()->with('userPhotos')->get();

        foreach ($followingUsers as $followingUser) {
            $this->followStatus[$followingUser->id] = $followingUser->followers->contains(Auth::id());
        }

        $this->following = $followingUsers;
    }

    public function toggleFollow($userId)
    {
        $user = User::find($userId);

        if ($this->followStatus[$userId]) {
            $user->followers()->detach(Auth::id());
            $this->followStatus[$userId] = false;
        } else {
            $user->followers()->attach(Auth::id());
            $this->followStatus[$userId] = true;
        }
    }

    public function getAvatar($userId)
    {
        $path = UserPhoto::where('user_id', $userId)
            ->latest()
            ->value('photo_path');
        return $path ? Storage::url($path) : asset('images/users/avatar.jpg');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.user-following');
    }
}
