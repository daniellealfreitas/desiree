<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class Profile extends Component
{
    public function likePost($postId)
    {
        $post = Post::find($postId);
        $post->likes()->attach(Auth::id());

        // Increment ranking points for the user
        Auth::user()->incrementRankingPoints(10);

        session()->flash('message', 'Post liked successfully!');
    }

    public function render()
    {
        return view('livewire.profile');
    }
}