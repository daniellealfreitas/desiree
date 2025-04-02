<x-layouts.app :title="__('Dashboard')">
    <x-flux-container>
        <div class="grid md:grid-cols-3 gap-6">
            <!-- Coluna Esquerda: Sidebar -->
            <div class="space-y-6">
                <!-- Perfil do Usuário -->
                <x-flux-card class="shadow-md">
                    <x-slot name="header">
                        <div class="relative h-32 bg-cover bg-center rounded-t-lg" 
                             style="background:url({{ asset('images/users/capa.jpg') }}); background-size: cover; background-position: center;">
                        </div>
                    </x-slot>
                    <div class="relative z-10 -mt-12 flex flex-col items-center">
                        <x-flux-image src="{{ asset('images/users/avatar.jpg') }}" alt="Foto de Perfil" class="w-24 h-24 rounded-full border-4 border-white shadow-lg" />
                        <x-flux-heading class="text-xl font-semibold mt-2">Nome do Usuário</x-flux-heading>
                        <x-flux-text class="text-gray-600">@usuario</x-flux-text>
                        
                        <!-- Estatísticas -->
                        <div class="mt-4 flex justify-around w-full">
                            <div class="text-center">
                                <x-flux-heading class="text-lg font-semibold">4</x-flux-heading>
                                <x-flux-text class="text-gray-500">Posts</x-flux-text>
                            </div>
                            <div class="text-center">
                                <x-flux-heading class="text-lg font-semibold">71</x-flux-heading>
                                <x-flux-text class="text-gray-500">Seguindo</x-flux-text>
                            </div>
                            <div class="text-center">
                                <x-flux-heading class="text-lg font-semibold">0</x-flux-heading>
                                <x-flux-text class="text-gray-500">Seguidores</x-flux-text>
                            </div>
                        </div>
                    </div>
                </x-flux-card>
                
                <!-- Últimos Acessos e Perfis Sugeridos -->
                <x-flux-card class="shadow-md">
                    <x-slot name="header">
                        <x-flux-heading class="text-white bg-gray-800 p-3 rounded-t-lg font-semibold">Últimos Acessos</x-flux-heading>
                    </x-slot>
                    <x-flux-list class="p-3 space-y-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <x-flux-list-item class="flex items-center space-x-3">
                                <x-flux-image src="{{ asset('images/users/avatar' . $i . '.jpg') }}" alt="Usuário {{ $i }}" class="w-10 h-10 rounded-full" />
                                <span>Usuário {{ $i }}</span>
                            </x-flux-list-item>
                        @endfor
                    </x-flux-list>
                    <x-slot name="footer">
                        <x-flux-heading class="text-white bg-gray-800 p-3 mt-4 rounded-t-lg font-semibold">Perfis Sugeridos</x-flux-heading>
                    </x-slot>
                    <x-flux-list class="p-3 space-y-2">
                        @for ($i = 6; $i <= 10; $i++)
                            <x-flux-list-item class="flex items-center space-x-3">
                                <x-flux-image src="{{ asset('images/users/avatar' . $i . '.jpg') }}" alt="Usuário {{ $i }}" class="w-10 h-10 rounded-full" />
                                <span>Usuário {{ $i }}</span>
                            </x-flux-list-item>
                        @endfor
                    </x-flux-list>
                </x-flux-card>
            </div>
            
            <!-- Coluna Direita: Feed de Postagens -->
            <div class="col-span-2 space-y-6">
                <!-- Formulário para Criar Postagem -->
                <x-flux-card class="shadow-md">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <x-flux-textarea name="text_content" rows="3" placeholder="Compartilhe o que você pensa com fotos ou vídeos..." class="w-full p-3 border border-gray-300 rounded-lg" />
                        <div class="flex justify-between mt-3">
                            <div class="flex space-x-4">
                                <label for="image_content" class="cursor-pointer flex items-center text-gray-500">
                                    📷 <input id="image_content" name="image_content" type="file" accept="image/*" class="hidden">
                                </label>
                                <label for="video_content" class="cursor-pointer flex items-center text-gray-500">
                                    🎥 <input id="video_content" name="video_content" type="file" accept="video/*" class="hidden">
                                </label>
                            </div>
                            <x-flux-button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Postar</x-flux-button>
                        </div>
                    </form>
                </x-flux-card>
                
                <!-- Lista de Postagens -->
                @for ($i = 0; $i < 3; $i++)
                    <x-flux-card class="shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center space-x-3">
                                <x-flux-image src="{{ asset('images/users/avatar.jpg') }}" alt="Avatar" class="w-10 h-10 rounded-full" />
                                <div>
                                    <x-flux-heading class="font-semibold">Nome do Usuário</x-flux-heading>
                                    <x-flux-text class="text-gray-500 text-sm">@usuario</x-flux-text>
                                </div>
                            </div>
                            <!-- Botão de Curtir via Livewire 3.0 -->
                            <livewire:like-button />
                        </div>
                        <x-flux-image src="{{ asset('images/posts/post.jpg') }}" alt="Imagem do Post" class="w-full rounded-lg mb-4" />
                        <x-flux-text class="text-gray-700">Texto da postagem...</x-flux-text>
                        <x-flux-input type="text" class="w-full p-2 border border-gray-300 rounded-lg mt-3" placeholder="Escreva um comentário..." />
                    </x-flux-card>
                @endfor
            </div>
        </div>
    </x-flux-container>
</x-layouts.app>
