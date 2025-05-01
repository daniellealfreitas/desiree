<div>
    {{-- Profile header  --}}
    <div
        x-data="{ show: false }"
        x-init="setTimeout(() => show = true, 200)"
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-show="show"
        class="relative w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">

        <div id="profile_header" class="relative w-full">
            {{-- Foto de capa Backgrond cover --}}
            <div class="w-full h-80 bg-cover bg-center" style="background-image: url('{{ $this->cover() ?? asset('images/default-banner.jpg') }}');"></div>
            
            <div class="absolute left-8 top-1/2 -translate-y-1/2 flex items-center gap-6">
                <div class="w-48 h-48 rounded-full border-4 border-white overflow-hidden shadow-xl">
                    <img src="{{ $this->avatar() ?? asset('images/default-avatar.jpg') }}" class="w-full h-full object-cover" />
                </div>
                <div class="bg-zinc-800 opacity-50 p-4 rounded-lg shadow-lg">
                    <h2 class="text-3xl font-bold text-white drop-shadow-lg">{{ $user->name }}</h2>
                    <a href="/{{ $user->username }}" class="text-lg text-gray-200 drop-shadow-md hover:underline">
                        {{ '@'. $user->username }}
                    </a>
                </div>
            </div>
        </div>

        <div id="profile_navigation" class="flex flex-wrap items-center justify-between border-t border-gray-200 dark:border-gray-700 px-6 py-3 mt-4 text-sm text-gray-700 dark:text-gray-300">
            <div class="flex flex-wrap gap-4">
               <flux:button.group>
                    <flux:button variant="ghost" icon="photo" >
                        Imagens ({{ $this->imagesCount() }})
                    </flux:button>
                    <flux:button variant="ghost" icon="video-camera" >
                        Vídeos
                    </flux:button>
                    <flux:button variant="ghost" icon="users" >
                        Seguindo: {{ $this->followingCount() }}
                    </flux:button>
                    <flux:button variant="ghost" icon="users">
                        Seguidores: {{ $this->followersCount() }}
                    </flux:button>
                    <flux:button variant="ghost" icon="rss" >
                        Postagens: {{ $this->postsCount() }}
                    </flux:button>
                    <flux:button variant="ghost" icon="currency-dollar">
                        Pagar um Drink
                    </flux:button>
               </flux:button.group>
            </div>

            <div class="flex gap-2">
                @if($user->id !== Auth::id())
                    <button wire:click="toggleFollow({{ $user->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition">
                        {{ $followStatus[$user->id] ? 'Deixar de Seguir' : 'Seguir' }}
                    </button>
                    
                @endif
            </div>
        </div>
    </div>
    <div class="flex gap-6 mt-6">
        <div class="w-1/3">
            <section id="progress-bar">
                <flux:text>Preenchimento de perfil</flux:text>
                <div class="relative mx-5 my-10">
                    <div class="mb-4 flex h-5 overflow-hidden rounded text-xs border border-gray-500">
                        <div style="width: 10%" class="bg-green-500 transition-all duration-500 ease-out"></div>
                    </div>
                    <div class="mb-2 flex items-center justify-between text-xs">
                        <div class="text-gray-600">Progresso</div>
                        <div class="text-gray-600">20%</div>
                    </div>
                </div>
            </section>
            <section id="ranking" class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Ranking</h3>
                <div class="flex flex-col gap-4">
                    @foreach($topUsers as $rank)
                        <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden">
                                    <img src="{{ $rank->avatar ?? asset('images/default-avatar.jpg') }}" class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $rank->name }}</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ '@' . $rank->username }}</p>
                                    <flux:text>Level 1</flux:text>
                                </div>
                            </div>
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ $rank->ranking_points }} pontos
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            <section id="procurando" class="mt-6">
                <flux:text>
                    <h2>Procurando</h2>
                    <p>Procurando por um amor verdadeiro, alguém que me faça feliz e que me faça rir. Se você é essa pessoa, entre em contato!</p>
                </flux:text>
            </section>
            <section id="skills" class="mt-6">
                <flux:text>
                    <ul>
                        <li>17 cm de pica </li> Comprovado. por zilandaxxx, delilah, 
                    </ul>
                </flux:text>
            </section>
        </div>
        <div class="w-2/3">
            <section id="create-post">
                <livewire:create-post />
                <livewire:postfeed />
            </section>
            
        </div>
    </div>
</div>