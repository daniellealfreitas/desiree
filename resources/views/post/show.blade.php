<x-layouts.app :title="$post->title">
    <div class=" h-screen h-full py-2 sm:py-8 lg:py-2">
        <div class="mx-auto max-w-screen-md px-4 md:px-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $post->title }}</h1>
            <div class="flex items-center space-x-3 mt-4">
                <img src="{{ asset($post->user->userPhotos->first()->photo_path ?? 'images/users/default.jpg') }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <h4 class="font-semibold">{{ $post->user->name }}</h4>
                    <p class="text-sm text-gray-500">
                        <a href="/{{ $post->user->username }}" class="hover:underline"> {{ '@'.$post->user->username }}</a>
                    </p>
                </div>
            </div>
            <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="mt-4 rounded-lg shadow-lg">
            <p class="mt-6 text-gray-700 dark:text-gray-300">{{ $post->content }}</p>
            @if($post->video)
                <div class="mt-4">
                    <video controls class="w-full rounded-lg shadow-lg">
                        <source src="{{ asset($post->video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @endif
            <div class="mt-2 text-xs text-gray-400">
                <span>Created: {{ $post->created_at->format('M d, Y H:i') }}</span>
                @if($post->updated_at && $post->updated_at != $post->created_at)
                    <span class="ml-2">Updated: {{ $post->updated_at->format('M d, Y H:i') }}</span>
                @endif
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">Likes: {{ $post->likes()->count() }}</span>
                <span class="ml-2 text-sm text-gray-500">Comments: {{ $post->comments()->count() }}</span>
            </div>
            @if(Auth::id() === $post->user_id)
                <div class="mt-4">
                    <button wire:click="openDeleteModal({{ $post->id }})" class="text-red-500 hover:text-red-700 p-1" title="Excluir postagem">
                        <x-flux::icon icon="trash" variant="outline" class="w-5 h-5" />
                    </button>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>