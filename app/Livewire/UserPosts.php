<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use App\Models\UserPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class UserPosts extends Component
{
    public $userId;
    public $posts = [];
    public $showModal = false;
    public $likeStatus = [];

    // Livewire 3 usa o método getListeners() em vez de $listeners
    protected function getListeners()
    {
        return ['show-user-posts' => 'loadPosts'];
    }

    public function loadPosts($userId)
    {
        $this->userId = $userId;
        $this->showModal = true;

        $posts = Post::where('user_id', $this->userId)
            ->with(['user', 'comments', 'likes'])
            ->latest()
            ->get();

        foreach ($posts as $post) {
            $this->likeStatus[$post->id] = $post->likes->contains('user_id', Auth::id());
        }

        $this->posts = $posts;
    }

    public function toggleLike($postId)
    {
        $post = Post::find($postId);

        if ($this->likeStatus[$postId]) {
            $post->likes()->where('user_id', Auth::id())->delete();
            $this->likeStatus[$postId] = false;
        } else {
            $post->likes()->create(['user_id' => Auth::id()]);
            $this->likeStatus[$postId] = true;
        }

        // Refresh the post to get updated counts
        $this->posts = $this->posts->map(function ($p) use ($postId, $post) {
            if ($p->id === $postId) {
                $post->load('likes');
                return $post;
            }
            return $p;
        });
    }

    public function getAvatar($userId)
    {
        $path = UserPhoto::where('user_id', $userId)
            ->latest()
            ->value('photo_path');
        return $path ? Storage::url($path) : asset('images/default-avatar.jpg');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.user-posts');
    }
}
