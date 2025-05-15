<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class ReelsFeed extends Component
{
    public $posts;
    public $currentIndex = 0;

    // Livewire 3 usa o método getListeners() em vez de $listeners
    protected function getListeners()
    {
        return ['likeUpdated' => 'handleLikeUpdated'];
    }

    public function mount()
    {
        $this->loadPosts();
    }

    public function loadPosts()
    {
        $this->posts = Post::whereNotNull('video')
            ->with(['user', 'user.userPhotos', 'comments'])
            ->latest()
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'video' => asset('storage/' . $post->video),
                    'title' => $post->title ?? '',
                    'content' => $post->content ?? '',
                    'created_at' => $post->created_at->diffForHumans(),
                    'user' => [
                        'id' => $post->user->id ?? null,
                        'name' => $post->user->name ?? 'Usuário',
                        'username' => $post->user->username ?? '',
                        'avatar' => $post->user->userPhotos->first()
                            ? asset('storage/' . $post->user->userPhotos->first()->photo_path)
                            : asset('images/default-avatar.jpg')
                    ],
                    'likes_count' => $post->likes()->count(),
                    'comments_count' => $post->comments()->count() ?? 0,
                    'liked_by_user' => Auth::check() && $post->likes()->where('user_id', Auth::id())->exists()
                ];
            })
            ->toArray();
    }

    public function handleLikeUpdated($data)
    {
        // Atualizar o estado do post que foi curtido/descurtido
        foreach ($this->posts as $index => $post) {
            if ($post['id'] == $data['postId']) {
                $this->posts[$index]['likes_count'] = $data['likesCount'];
                $this->posts[$index]['liked_by_user'] = $data['isLiked'];
                break;
            }
        }
    }

    public function likePost($postId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $post = Post::find($postId);

        if (!$post) {
            return;
        }

        $isLiked = $post->likes()->where('user_id', Auth::id())->exists();

        if ($isLiked) {
            // Remover o like
            $post->likes()->where('user_id', Auth::id())->delete();
            $likesCount = $post->likes()->count();
            $isLiked = false;
        } else {
            // Adicionar o like
            $like = new Like();
            $like->user_id = Auth::id();
            $like->post_id = $postId;
            $like->save();

            $likesCount = $post->likes()->count();
            $isLiked = true;
        }

        // Atualizar o estado do post
        foreach ($this->posts as $index => $p) {
            if ($p['id'] == $postId) {
                $this->posts[$index]['likes_count'] = $likesCount;
                $this->posts[$index]['liked_by_user'] = $isLiked;
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.reels-feed')
            ->layout('components.layouts.reels', [
                'title' => 'Reels'
            ]);
    }
}
