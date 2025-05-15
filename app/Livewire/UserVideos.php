<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class UserVideos extends Component
{
    public $userId;
    public $videos = [];
    public $showModal = false;
    public $currentVideo = null;

    // Livewire 3 usa o método getListeners() em vez de $listeners
    protected function getListeners()
    {
        return ['show-user-videos' => 'loadVideos'];
    }

    public function loadVideos($userId)
    {
        $this->userId = $userId;
        $this->showModal = true;

        // Get all posts with videos from this user
        $posts = Post::where('user_id', $this->userId)
            ->whereNotNull('video')
            ->latest()
            ->get();

        $this->videos = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'url' => Storage::url($post->video),
                'created_at' => $post->created_at->format('d/m/Y H:i'),
                'likes_count' => $post->likes()->count(),
                'comments_count' => $post->comments()->count(),
                'content' => $post->content,
            ];
        })->toArray();
    }

    public function viewVideo($index)
    {
        $this->currentVideo = $this->videos[$index];
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->currentVideo = null;
    }

    public function render()
    {
        return view('livewire.user-videos');
    }
}
