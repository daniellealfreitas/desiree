<?php

use function Livewire\Volt\{state, computed, action};
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

state([
    'limit' => 5,
    'posts' => fn() => Post::with(['user', 'likedByUsers'])
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

    $post->isLikedBy($user)
        ? $post->likedByUsers()->detach($user->id)
        : $post->likedByUsers()->attach($user->id);
});

?>

<div>
    @foreach ($posts as $post)
        <div class="p-6 mb-6 border border-gray-200 rounded-lg shadow-md">
            <div class="flex items-center space-x-3 mb-4">
                <img src="{{ asset('images/users/' . ($post->user->profile ?? 'default.jpg')) }}" class="w-10 h-10 rounded-full">
                <div>
                    <h4 class="font-semibold">{{ $post->user->name }}</h4>
                    <p class="text-sm text-gray-500">@{{ $post->user->username }}</p>
                </div>
            </div>

            @if ($post->image)
                <img src="{{ asset( $post->image) }}" class="w-full rounded-lg mb-4">
            @endif

            <p class="text-gray-700">{{ $post->content }}</p>

            <div class="mt-3 flex items-center space-x-2">
                <button
                    wire:click="toggleLike({{ $post->id }})"
                    class="{{ $post->isLikedBy(auth()->user()) ? 'text-red-600' : 'text-gray-400' }}"
                >
                    ❤️ Curtir
                </button>
                <span>{{ $post->likedByUsers->count() }} Curtida</span>
            </div>

            <input
                type="text"
                class="w-full p-2 border border-gray-300 rounded-lg mt-3"
                placeholder="Write a comment..."
            >
        </div>
    @endforeach

    <button wire:click="loadMore" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Load More
    </button>
</div>
