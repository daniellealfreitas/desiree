<x-layouts.app :title="__('Dashboard')">
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Container para coluna esquerda -->
        <div class="col-span-1 space-y-6">
            <!--   Perfil -->
            <div class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
                <div class="relative h-32 bg-cover bg-center rounded-t-lg" 
                    style="background:url({{ asset('images/users/capa.jpg') }}); background-size: cover; background-position: center;">
                </div>
                <div class="relative z-10 -mt-12 flex flex-col items-center">
                    <img src="{{ asset('images/users/avatar.jpg') }}" alt="Foto de Perfil" 
                        class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                    <h2 class="text-xl font-semibold mt-2">Nome do Usuário</h2>
                    <p class="text-gray-600">@usuario</p>
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
                    @for ($i = 1; $i <= 5; $i++)
                        <li class="flex items-center space-x-3">
                            <img src="{{ asset('images/users/avatar' . $i . '.jpg') }}" class="w-10 h-10 rounded-full">
                            <span>Usuário {{ $i }}</span>
                        </li>
                    @endfor
                </ul>
                <h3 class="text-white bg-gray-800 p-3 mt-4 rounded-t-lg font-semibold">Perfis Sugeridos</h3>
                <ul class="p-3 space-y-2">
                    @for ($i = 6; $i <= 10; $i++)
                        <li class="flex items-center space-x-3">
                            <img src="{{ asset('images/users/avatar' . $i . '.jpg') }}" class="w-10 h-10 rounded-full">
                            <span>Usuário {{ $i }}</span>
                        </li>
                    @endfor
                </ul>
            </div>
        </div>
        <!-- Container para Feed de Postagens -->
        <div class="col-span-2 space-y-6">
            <livewire:create-post />
            <livewire:postfeed />
        </div>
    </div>
</x-layouts.app>
