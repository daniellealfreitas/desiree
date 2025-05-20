<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\UserPoint;
use Illuminate\Support\Facades\Auth;

class VideoFeed extends Component
{
    public $posts = [];
    public $currentIndex = 0;

    public function mount()
    {
        $this->loadPosts();
    }

    public function loadPosts()
    {
        $this->posts = Post::whereNotNull('video')
            ->with(['user', 'user.userPhotos', 'comments.user.userPhotos'])
            ->latest()
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'video' => asset('storage/' . $post->video),
                    'title' => $post->title ?? '',
                    'content' => $post->content ?? '',
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
                    'liked_by_user' => Auth::check() && $post->likes()->where('user_id', Auth::id())->exists(),
                    'showComments' => false,
                    'comments' => $post->comments()->with(['user.userPhotos'])->latest()->take(5)->get()->map(function($comment) {
                        return [
                            'id' => $comment->id,
                            'body' => $comment->body,
                            'created_at' => $comment->created_at->diffForHumans(),
                            'user' => [
                                'id' => $comment->user->id,
                                'name' => $comment->user->name,
                                'username' => $comment->user->username,
                                'avatar' => $comment->user->userPhotos->first()
                                    ? asset('storage/' . $comment->user->userPhotos->first()->photo_path)
                                    : asset('images/default-avatar.jpg')
                            ]
                        ];
                    })->toArray()
                ];
            })
            ->toArray();
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

            // Remove notificação
            Notification::where([
                'sender_id' => Auth::id(),
                'post_id' => $post->id,
                'type' => 'like'
            ])->delete();

            // Remove pontos (apenas se o post não for do próprio usuário)
            if ($post->user_id !== Auth::id()) {
                UserPoint::removePoints(
                    $post->user_id,
                    'like',
                    5,
                    "Perdeu curtida de " . Auth::user()->name,
                    $post->id,
                    Post::class
                );
            }

            $likesCount = $post->likes()->count();
            $isLiked = false;
        } else {
            // Adicionar o like
            $like = new Like();
            $like->user_id = Auth::id();
            $like->post_id = $postId;
            $like->save();

            // Adiciona pontos ao usuário que curtiu (recompensa por engajamento)
            UserPoint::addPoints(
                Auth::id(),
                'like',
                2,
                "Curtiu postagem de " . ($post->user_id === Auth::id() ? "sua autoria" : $post->user->name),
                $post->id,
                Post::class
            );

            // Adiciona pontos ao autor do post (se não for o mesmo usuário)
            if ($post->user_id !== Auth::id()) {
                UserPoint::addPoints(
                    $post->user_id,
                    'like_received',
                    5,
                    "Recebeu curtida de " . Auth::user()->name,
                    $post->id,
                    Post::class
                );

                // Cria notificação se não for post próprio
                Notification::create([
                    'user_id' => $post->user_id,
                    'sender_id' => Auth::id(),
                    'type' => 'like',
                    'post_id' => $post->id
                ]);
            }

            // Dispara animação de recompensa
            $this->dispatch('reward-earned', points: 2);

            $likesCount = $post->likes()->count();
            $isLiked = true;
        }

        // Atualizar o estado do post imediatamente
        foreach ($this->posts as $index => $p) {
            if ($p['id'] == $postId) {
                $this->posts[$index]['likes_count'] = $likesCount;
                $this->posts[$index]['liked_by_user'] = $isLiked;
                break;
            }
        }

        // Disparar evento para atualizar a UI
        $this->dispatch('postLiked', [
            'postId' => $postId,
            'likesCount' => $likesCount,
            'isLiked' => $isLiked
        ]);
    }

    public function addComment($postId, $commentText)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (empty(trim($commentText))) {
            return;
        }

        $post = Post::find($postId);

        if (!$post) {
            return;
        }

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'body' => $commentText
        ]);

        // Adiciona pontos ao usuário que comentou
        UserPoint::addPoints(
            Auth::id(),
            'comment',
            5,
            "Comentou na postagem de " . ($post->user_id === Auth::id() ? "sua autoria" : $post->user->name),
            $comment->id,
            Comment::class
        );

        // Adiciona pontos ao autor do post (se não for o mesmo usuário)
        if ($post->user_id !== Auth::id()) {
            UserPoint::addPoints(
                $post->user_id,
                'comment_received',
                3,
                "Recebeu comentário de " . Auth::user()->name,
                $comment->id,
                Comment::class
            );

            // Cria notificação para o autor do post
            Notification::create([
                'user_id' => $post->user_id,
                'sender_id' => Auth::id(),
                'type' => 'comment',
                'post_id' => $post->id,
                'comment_id' => $comment->id
            ]);
        }

        // Dispara animação de recompensa
        $this->dispatch('reward-earned', points: 5);

        // Atualizar a lista de comentários do post
        $newComment = [
            'id' => $comment->id,
            'body' => $comment->body,
            'created_at' => $comment->created_at->diffForHumans(),
            'user' => [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'username' => Auth::user()->username,
                'avatar' => Auth::user()->userPhotos->first()
                    ? asset('storage/' . Auth::user()->userPhotos->first()->photo_path)
                    : asset('images/default-avatar.jpg')
            ]
        ];

        foreach ($this->posts as $index => $p) {
            if ($p['id'] == $postId) {
                // Adicionar o novo comentário no início da lista
                array_unshift($this->posts[$index]['comments'], $newComment);
                // Atualizar o contador de comentários
                $this->posts[$index]['comments_count']++;
                break;
            }
        }

        // Disparar evento para atualizar a UI
        $this->dispatch('commentAdded', [
            'postId' => $postId,
            'comment' => $newComment,
            'commentsCount' => $post->comments()->count()
        ]);
    }

    public function render()
    {
        return view('livewire.video-feed')
            ->layout('components.layouts.app', [
                'title' => 'Reels'
            ]);
    }
}
