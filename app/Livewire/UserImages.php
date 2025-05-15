<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class UserImages extends Component
{
    public $userId;
    public $images = [];
    public $showModal = false;
    public $currentImage = null;

    // Livewire 3 usa o método getListeners() em vez de $listeners
    protected function getListeners()
    {
        return ['show-user-images' => 'loadImages'];
    }

    public function loadImages($userId)
    {
        $this->userId = $userId;
        $this->showModal = true;

        // Get all posts with images from this user
        $posts = Post::where('user_id', $this->userId)
            ->whereNotNull('image')
            ->latest()
            ->get();

        $this->images = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'url' => Storage::url($post->image),
                'created_at' => $post->created_at->format('d/m/Y H:i'),
                'likes_count' => $post->likes()->count(),
                'comments_count' => $post->comments()->count(),
                'content' => $post->content,
            ];
        })->toArray();
    }

    public function viewImage($index)
    {
        $this->currentImage = $this->images[$index];
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->currentImage = null;
    }

    public function render()
    {
        return view('livewire.user-images');
    }
}
