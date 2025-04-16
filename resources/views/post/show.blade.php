<x-layouts.app :title="$post->title">
    <div class=" h-screen h-full py-2 sm:py-8 lg:py-2">
        <div class="mx-auto max-w-screen-md px-4 md:px-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $post->title }}</h1>
            <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="mt-4 rounded-lg shadow-lg">
            <p class="mt-6 text-gray-700 dark:text-gray-300">{{ $post->content }}</p>
        </div>
    </div>
</x-layouts.app>