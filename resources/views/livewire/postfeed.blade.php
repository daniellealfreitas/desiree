<?php

use function Livewire\Volt\{state, computed, action};
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

state([
    'limit' => 5,
    'newComment' => [],
    'showDeleteModal' => false,
    'postToDelete' => null,
    'posts' => fn() => Post::with([
        'user.userPhotos' => function($query) {
            $query->latest()->take(1);
        },
        'likedByUsers',
        'comments.user.userPhotos' => function($query) {
            $query->latest()->take(1);
        }
    ])
        ->when(request()->route('username'), function($query) {
            // Filtra posts do usuário correspondente ao 'username' da rota
            $username = request()->route('username');
            $user = User::where('username', $username)->first();
            if ($user) {
                return $query->where('user_id', $user->id);
            }
        })
        ->latest()
        ->take($this->limit)
        ->get()
]);


$loadMore = action(function () {
    $this->limit += 5;

    // Atualiza os posts para refletir o novo limite
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

$toggleLike = action(function ($postId) {
    $post = Post::findOrFail($postId);
    $user = Auth::user();

    if (!$user) return;

    $wasLiked = $post->isLikedBy($user);

    if ($wasLiked) {
        // Remove curtida
        $post->likedByUsers()->detach($user->id);
        // Remove notificação
        Notification::where([
            'sender_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'like'
        ])->delete();

        // Remove pontos (apenas se o post não for do próprio usuário)
        if ($post->user_id !== $user->id) {
            \App\Models\UserPoint::removePoints(
                $post->user_id,
                'like',
                5,
                "Perdeu curtida de {$user->name}",
                $post->id,
                Post::class
            );
        }
    } else {
        // Adiciona curtida
        $post->likedByUsers()->attach($user->id);

        // Adiciona pontos ao usuário que curtiu (recompensa por engajamento)
        \App\Models\UserPoint::addPoints(
            $user->id,
            'like',
            2,
            "Curtiu postagem de " . ($post->user_id === $user->id ? "sua autoria" : $post->user->name),
            $post->id,
            Post::class
        );

        // Adiciona pontos ao autor do post (se não for o mesmo usuário)
        if ($post->user_id !== $user->id) {
            \App\Models\UserPoint::addPoints(
                $post->user_id,
                'like_received',
                5,
                "Recebeu curtida de {$user->name}",
                $post->id,
                Post::class
            );
        }

        // Dispara animação de recompensa
        $this->dispatch('reward-earned', points: 2);

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

    $post = Post::findOrFail($postId);
    $user = Auth::user();

    $comment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $postId,
        'body' => $this->newComment[$postId]
    ]);

    // Adiciona pontos ao usuário que comentou
    \App\Models\UserPoint::addPoints(
        $user->id,
        'comment',
        5,
        "Comentou na postagem de " . ($post->user_id === $user->id ? "sua autoria" : $post->user->name),
        $comment->id,
        Comment::class
    );

    // Adiciona pontos ao autor do post (se não for o mesmo usuário)
    if ($post->user_id !== $user->id) {
        \App\Models\UserPoint::addPoints(
            $post->user_id,
            'comment_received',
            3,
            "Recebeu comentário de {$user->name}",
            $comment->id,
            Comment::class
        );

        // Cria notificação para o autor do post
        Notification::create([
            'user_id' => $post->user_id,
            'sender_id' => $user->id,
            'type' => 'comment',
            'post_id' => $post->id,
            'comment_id' => $comment->id
        ]);
    }

    // Dispara animação de recompensa
    $this->dispatch('reward-earned', points: 5);

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

$openDeleteModal = action(function ($postId) {
    $this->showDeleteModal = true;
    $this->postToDelete = $postId;
});

$closeDeleteModal = action(function () {
    $this->showDeleteModal = false;
    $this->postToDelete = null;
});

$deletePost = action(function ($postId) {
    try {
        $post = Post::findOrFail($postId);

        // Verificar se o usuário atual é o dono do post
        if (Auth::id() !== $post->user_id) {
            session()->flash('error', 'Você não tem permissão para excluir este post.');
            return;
        }

        // Excluir arquivos do storage
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        if ($post->video) {
            Storage::disk('public')->delete($post->video);
        }

        // Excluir o post (as relações serão excluídas automaticamente devido às restrições de chave estrangeira)
        $post->delete();

        // Atualizar a lista de posts
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

        // Fechar o modal e mostrar mensagem de sucesso
        $this->showDeleteModal = false;
        $this->postToDelete = null;
        session()->flash('message', 'Post excluído com sucesso!');
    } catch (\Exception $e) {
        session()->flash('error', 'Erro ao excluir o post: ' . $e->getMessage());
    }
});

?>

<div>
    @foreach ($posts as $post)
        <div class="p-6 mb-6 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-md">
            <div class="flex justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ !empty($post->user->userPhotos->first()) ? Storage::url($post->user->userPhotos->first()->photo_path) : asset('images/users/default.jpg') }}"
                         class="w-10 h-10 rounded-full object-cover">
                    <div>
                        <h4 class="font-semibold text-gray-300">{{ $post->user->name }}</h4>
                        <p class="text-sm text-gray-500">
                            <a href="/{{ $post->user->username }}" class="hover:underline"> {{ '@'.$post->user->username }}</a>
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">{{ Carbon::parse($post->created_at)->diffForHumans() }}</span>

                    @if(Auth::id() === $post->user_id)
                        <button
                            wire:click="openDeleteModal({{ $post->id }})"
                            class="text-red-500 hover:text-red-700 p-1"
                            title="Excluir postagem"
                        >
                            <x-flux::icon
                                icon="trash"
                                variant="outline"
                                class="w-5 h-5"
                            />
                        </button>
                    @endif
                </div>
            </div>

            @if ($post->image)
                <img src="{{ Storage::url($post->image) }}" class="w-full rounded-lg mb-4">
            @endif

            @if ($post->video)
                <video controls class="w-full rounded-lg mb-4">
                    <source src="{{ Storage::url($post->video) }}" type="video/mp4">
                    Seu navegador não suporta o elemento de vídeo.
                </video>
            @endif
            <p class="text-gray-700">{{ $post->content }}</p>

            <div class="mt-3 flex items-center space-x-2">
                <button
                    wire:click="toggleLike({{ $post->id }})"
                    class="flex items-center space-x-1 {{ $post->isLikedBy(auth()->user()) ? 'text-red-600' : 'text-gray-300' }}"
                >
                    <x-flux::icon
                        icon="heart"
                        variant="{{ $post->isLikedBy(auth()->user()) ? 'solid' : 'outline' }}"
                        class="w-5 h-5"
                    />
                    <span>{{ $post->isLikedBy(auth()->user()) ? 'Curtido' : 'Curtir' }}</span>
                </button>
                <div class="relative group">
                    <span class="text-gray-300">{{ $post->likedByUsers->count() }} Curtidas</span>

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
                        class="flex-1 p-2 border border-neutral-200 dark:border-neutral-700 rounded-lg text-gray-300"
                        placeholder="Escreva um comentário..."
                    >
                    <button type="submit" class="flex items-center space-x-1 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <x-flux::icon
                            icon="chat-bubble-left"
                            variant="solid"
                            class="w-4 h-4"
                        />
                        <span>Comentar</span>
                    </button>
                </form>

                <!-- Lista de comentários -->
                @foreach($post->comments as $comment)
                    <div class="flex items-start space-x-3 p-3 bg-zinc-700 rounded-lg hover:bg-zinc-600 transition border-neutral-200 dark:border-neutral-700">
                        <img src="{{ !empty($comment->user->userPhotos->first()) ? Storage::url($comment->user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                             class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <p class="font-semibold text-gray-300">
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

    <div class="mt-4 flex items-center space-x-2">
        <flux:button wire:click="loadMore" >
            Carregar mais postagens
        </flux:button>
    </div>

    <!-- Mensagem de sucesso ou erro -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 mt-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mt-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Modal de confirmação de exclusão -->
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-lg max-w-md w-full p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Tem certeza que deseja excluir esta postagem?
            </h2>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Esta ação não pode ser desfeita. Todos os comentários e curtidas relacionados a esta postagem também serão excluídos.
            </p>

            <div class="flex justify-end space-x-3">
                <flux:button
                    wire:click="closeDeleteModal"
                    variant="ghost"
                >
                    <x-flux::icon icon="x-mark" class="w-4 h-4 mr-1" />
                    Cancelar
                </flux:button>

                <flux:button
                    wire:click="deletePost({{ $postToDelete }})"
                    variant="danger"
                >
                    <x-flux::icon icon="trash" class="w-4 h-4 mr-1" />
                    Excluir
                </flux:button>
            </div>
        </div>
    </div>
    @endif
</div>
