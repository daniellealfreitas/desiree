<?php

use function Livewire\Volt\{state, computed, action};
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

state([
    'limit' => 5,
    'newComment' => [], // Change to an array to store comments per post
    'posts' => fn() => Post::with([
        'user.userPhotos' => function($query) {
            $query->latest()->take(1);
        }, 
        'likedByUsers', 
        'comments.user.userPhotos' => function($query) {
            $query->latest()->take(1);
        }
    ])
        ->latest()
        ->take($this->limit)
        ->get()
]);

$loadMore = action(function () {
    $this->limit += 5;
});

$toggleLike = action(function ($postId) {
    $post = Post::findOrFail($postId);
    $user = Auth::user();

    if (!$user) return;

    if ($post->isLikedBy($user)) {
        // Remove curtida
        $post->likedByUsers()->detach($user->id);
        // Remove notificação
        Notification::where([
            'sender_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'like'
        ])->delete();
    } else {
        // Adiciona curtida
        $post->likedByUsers()->attach($user->id);
        // Adiciona pontos ao usuário
        $user->increment('ranking_points', 10);
        // Cria notificação se não for post próprio
        if ($post->user_id !== $user->id) {
            Notification::create([
                'user_id' => $post->user_id,
                'sender_id' => $user->id,
                'type' => 'like',
                'post_id' => $post->id
            ]);
        }
    }

    // Atualiza os posts para refletir mudanças
    $this->posts = Post::with([
        'user.userPhotos' => function($query) {
            $query->latest()->take(1);
        }, 
        'likedByUsers', 
        'comments.user.userPhotos' => function($query) {
            $query->latest()->take(1);
        }
    ])
        ->latest()
        ->take($this->limit)
        ->get();
});

$addComment = action(function ($postId) {
    $this->validate([
        "newComment.$postId" => 'required|min:1' // Validate comment for the specific post
    ]);

    Comment::create([
        'user_id' => Auth::id(),
        'post_id' => $postId,
        'body' => $this->newComment[$postId]
    ]);
    
    // Adiciona pontos ao usuário
    Auth::user()->increment('ranking_points', 10);

    $this->newComment[$postId] = ''; // Clear the comment for the specific post
    $this->posts = Post::with([
        'user.userPhotos' => function($query) {
            $query->latest()->take(1);
        }, 
        'likedByUsers', 
        'comments.user.userPhotos' => function($query) {
            $query->latest()->take(1);
        }
    ])
        ->latest()
        ->take($this->limit)
        ->get();
});

?>

<div>
    @foreach ($posts as $post)
        <div class="p-6 mb-6 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-md">
            <div class="flex items-center space-x-3 mb-4">
                <img src="{{ !empty($post->user->userPhotos->first()) ? Storage::url($post->user->userPhotos->first()->photo_path) : asset('images/users/default.jpg') }}" 
                     class="w-10 h-10 rounded-full object-cover">
                <div>
                    <h4 class="font-semibold">{{ $post->user->name }}</h4>
                    <p class="text-sm text-gray-500">
                        <a href="/{{ $post->user->username }}" class="hover:underline"> {{ '@'.$post->user->username }}</a>
                    </p>
                </div>
            </div>

            @if ($post->image)
                <img src="{{ asset( $post->image) }}" class="w-full rounded-lg mb-4">
            @endif

            <p class="text-gray-700">{{ $post->body }}</p>

            <div class="mt-3 flex items-center space-x-2">
                <button
                    wire:click="toggleLike({{ $post->id }})"
                    class="{{ $post->isLikedBy(auth()->user()) ? 'text-red-600' : 'text-gray-400' }}"
                >
                    ❤️ Curtir
                </button>
                <div class="relative group">
                    <span>{{ $post->likedByUsers->count() }} Curtidas</span>
                    
                    <!-- Tooltip com lista de usuários -->
                    @if($post->likedByUsers->count() > 0)
                        <div class="absolute bottom-full left-0 mb-2 hidden group-hover:block bg-black text-white p-2 rounded-lg shadow-lg z-50 w-48">
                            <div class="text-sm">
                                @foreach($post->likedByUsers as $user)
                                    <div class="flex items-center space-x-2 mb-1">
                                        <img src="{{ !empty($user->userPhotos->first()) ? Storage::url($user->userPhotos->first()->photo_path) : asset('images/users/default.jpg') }}" 
                                             class="w-6 h-6 rounded-full object-cover">
                                        <span>{{ $user->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="absolute -bottom-1 left-4 w-3 h-3 bg-black transform rotate-45"></div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 space-y-4">
                <form wire:submit="addComment({{ $post->id }})" class="flex gap-2">
                    <input
                        wire:model="newComment.{{ $post->id }}"
                        type="text"
                        class="flex-1 p-2 border border-neutral-200 dark:border-neutral-700 rounded-lg"
                        placeholder="Escreva um comentário..."
                    >
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Comentar
                    </button>
                </form>

                <!-- Lista de comentários -->
                @foreach($post->comments as $comment)
                    <div class="flex items-start space-x-3 p-3 bg-zinc-700 rounded-lg hover:bg-zinc-600 transition border-neutral-200 dark:border-neutral-700">
                        <img src="{{ !empty($comment->user->userPhotos->first()) ? Storage::url($comment->user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}" 
                             class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <p class="font-semibold">
                                <a href="/{{ $comment->user->username }}" class="hover:underline">
                                    {{ $comment->user->username }}
                                </a>
                            </p>
                            <p class="text-gray-100">{{ $comment->body }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <button wire:click="loadMore" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Load More
    </button>
</div>
