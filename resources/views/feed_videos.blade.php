<x-layouts.app :title="__('Ultimos Videos')">
    <div class="bg-white dark:bg-zinc-800 h-screen h-full py-6 sm:py-8 lg:py-12">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
            @php
                $posts = \App\Models\Post::whereNotNull('video')->latest()->get();
            @endphp
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 xl:gap-8">
                @foreach ($posts as $post)
                    <!-- video - start -->
                    <a href="{{ route('post.show', ['post' => $post->id]) }}"
                        class="group relative flex h-48 items-end overflow-hidden rounded-lg bg-gray-100 shadow-lg md:h-80">
                        <video controls class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110">
                            <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
                            Seu navegador não suporta o elemento de vídeo.
                        </video>
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-50"></div>
                        <span class="relative ml-4 mb-3 inline-block text-sm text-white md:ml-5 md:text-lg">{{ $post->title ?? '' }}</span>
                    </a>
                    <!-- video - end -->
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
