<x-layouts.app :title="__('Postagens')">
    <h1 class="text-2xl font-bold mb-4">Postagens</h1>
    @foreach($posts as $post)
        <div class="bg-white p-4 rounded-lg shadow mb-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('storage/' . ($post->user->profile_photo_path ?? 'default.png')) }}" alt="Avatar" class="w-10 h-10 rounded-full">
                    <div>
                        <h3 class="font-semibold">{{ $post->user->name }}</h3>
                        <p class="text-gray-500 text-sm">@{{ $post->user->email }}</p>
                    </div>
                </div>
                <form action="{{ route('likes.toggle', $post->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        @if($post->likes->where('user_id', auth()->id())->count() > 0)
                            Descurtir
                        @else
                            Curtir
                        @endif
                    </button>
                </form>
            </div>
            <p class="text-gray-700 mb-2">{{ $post->content }}</p>
            @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" alt="Imagem do Post" class="w-full rounded-lg mb-2">
            @endif
            @if($post->video)
                <video controls class="w-full rounded-lg mb-2">
                    <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
                </video>
            @endif
            <input type="text" placeholder="Escreva um comentário..." class="w-full p-2 border rounded-lg">
        </div>
</x-layouts.app>