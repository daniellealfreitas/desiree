<x-layouts.app :title="__('Dashboard')">
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Sidebar Perfil -->
        <div class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
            <!-- Capa do perfil -->
            <div class="relative h-32 bg-cover bg-center rounded-t-lg"
                style="background:url({{ asset('images/users/capa.jpg') }}); background-size: cover; background-position: center;">
            </div>

            <!-- Conteúdo do perfil -->
            <div class="relative z-10 -mt-12 flex flex-col items-center">
                <img src="{{ asset('images/users/avatar.jpg') }}" alt="Foto de Perfil"
                    class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                <h2 class="text-xl font-semibold mt-2">Nome do Usuário</h2>
                <p class="text-gray-600">@usuario</p>

                <!-- Estatísticas -->
                <div class="mt-4 flex justify-around w-full">
                    <div class="text-center">
                        <p class="text-lg font-semibold">4</p>
                        <p class="text-gray-500">Posts</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-semibold">71</p>
                        <p class="text-gray-500">Seguindo</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-semibold">0</p>
                        <p class="text-gray-500">Seguidores</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimos Acessos e Perfis Sugeridos -->
        <div class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
            <h3 class="text-white bg-gray-800 p-3 rounded-t-lg font-semibold">Últimos Acessos</h3>
            <ul class="p-3 space-y-2">
                <li class="flex items-center space-x-3">
                    <img src="{{ asset('images/users/avatar1.jpg') }}" class="w-10 h-10 rounded-full">
                    <span>Usuário 1</span>
                </li>
            </ul>

            <h3 class="text-white bg-gray-800 p-3 mt-4 rounded-t-lg font-semibold">Perfis Sugeridos</h3>
            <ul class="p-3 space-y-2">
                <li class="flex items-center space-x-3">
                    <img src="{{ asset('images/users/avatar6.jpg') }}" class="w-10 h-10 rounded-full">
                    <span>Usuário 6</span>
                </li>
            </ul>
        </div>

        <!-- Feed de Postagens -->
        <div class="col-span-2 space-y-6">
            <!-- Formulário de Nova Postagem -->
            <div class="p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <textarea name="text_content" rows="3" class="w-full p-3 border border-gray-300 rounded-lg"
                        placeholder="Compartilhe o que você pensa com fotos ou vídeos..."></textarea>
                    <div class="flex justify-between mt-3">
                        <div class="flex space-x-4">
                            <label for="image_content" class="cursor-pointer flex items-center text-gray-500">
                                📷 <input id="image_content" name="image_content" type="file" accept="image/*"
                                    class="hidden">
                            </label>
                            <label for="video_content" class="cursor-pointer flex items-center text-gray-500">
                                🎥 <input id="video_content" name="video_content" type="file" accept="video/*"
                                    class="hidden">
                            </label>
                        </div>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                            Postar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Postagens -->
            @foreach ($posts as $post)
                <div class="p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('images/users/avatar.jpg') }}" class="w-10 h-10 rounded-full">
                            <div>
                                <h4 class="font-semibold">{{ $post->user->name }}</h4>
                                <p class="text-gray-500 text-sm">@{{ $post->user->username }}</p>
                            </div>
                        </div>

                        <!-- Botão de Curtir funcionando com Livewire -->
                        <button wire:click="toggleLike({{ $post->id }})"
                            class="px-4 py-2 rounded-lg text-white font-semibold transition
                            {{ $post->liked ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-600 hover:bg-gray-700' }}">
                            {{ $post->liked ? 'Descurtir' : 'Curtir' }}
                        </button>
                    </div>
                    <img src="{{ asset('images/posts/' . $post->image) }}" class="w-full rounded-lg mb-4">
                    <p class="text-gray-700">{{ $post->content }}</p>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded-lg mt-3"
                        placeholder="Escreva um comentário...">
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
