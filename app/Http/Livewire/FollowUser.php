<?php
namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FollowUser extends Component
{
    public $user;
    public $isFollowing;

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->isFollowing = Auth::user()->following()->where('user_id', $this->user->id)->exists();
    }

    public function toggleFollow(): void
    {
        $authUser = Auth::user();

        if ($this->isFollowing) {
            $authUser->following()->detach($this->user->id);
        } else {
            $authUser->following()->attach($this->user->id);
        }

        $this->isFollowing = !$this->isFollowing;
    }

    public function render()
    {
        return view('livewire.follow-user');
    }
}
