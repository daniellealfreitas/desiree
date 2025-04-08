<?php
namespace App\Http\Livewire;

use Livewire\Volt\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostFeed extends Component
{
    public int $limit = 5;
    public $posts;

    public function mount()
    {
        $this->fetchPosts();
    }

    public function fetchPosts()
    {
        $this->posts = Post::with(['user', 'likedByUsers'])
            ->latest()
            ->take($this->limit)
            ->get();
    }

    public function loadMore()
    {
        $this->limit += 5;
        $this->fetchPosts();
    }

    public function toggleLike(int $postId)
    {
        $post = Post::findOrFail($postId);
        $user = Auth::user();

        if (!$user) return;

        $post->isLikedBy($user)
            ? $post->likedByUsers()->detach($user->id)
            : $post->likedByUsers()->attach($user->id);

        $this->fetchPosts();
    }

    public function render()
    {
        return view('livewire.postfeed');
    }
}
