<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class ReelsLikeButton extends Component
{
    public $postId;
    public $likesCount;
    public $isLiked;

    public function mount($postId)
    {
        $this->postId = $postId;
        $this->refreshLikeStatus();
    }

    public function refreshLikeStatus()
    {
        $post = Post::find($this->postId);
        
        if ($post) {
            $this->likesCount = $post->likes()->count();
            $this->isLiked = Auth::check() && $post->likes()->where('user_id', Auth::id())->exists();
        }
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            // Redirecionar para login se o usuário não estiver autenticado
            return redirect()->route('login');
        }

        $post = Post::find($this->postId);
        
        if (!$post) {
            return;
        }

        if ($this->isLiked) {
            // Remover o like
            $post->likes()->where('user_id', Auth::id())->delete();
            $this->isLiked = false;
            $this->likesCount--;
        } else {
            // Adicionar o like
            $like = new Like();
            $like->user_id = Auth::id();
            $like->post_id = $this->postId;
            $like->save();
            
            $this->isLiked = true;
            $this->likesCount++;
        }

        // Emitir evento para atualizar outros componentes
        $this->dispatch('likeUpdated', [
            'postId' => $this->postId,
            'likesCount' => $this->likesCount,
            'isLiked' => $this->isLiked
        ]);
    }

    public function render()
    {
        return view('livewire.reels-like-button');
    }
}
