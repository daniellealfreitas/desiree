<div>
    <div
        x-data="{ show: false }"
        x-init="setTimeout(() => show = true, 200)"
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-show="show"
        class="relative w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
        <div class="relative w-full">
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

        <div class="flex flex-wrap items-center justify-between border-t border-gray-200 dark:border-gray-700 px-6 py-3 mt-4 text-sm text-gray-700 dark:text-gray-300">
            <div class="flex flex-wrap gap-4">
                <button wire:click="showImages" class="hover:text-red-500">
                    Imagens ({{ $this->imagesCount() }})
                </button>
                <div>Seguindo ({{ $this->followingCount() }})</div>
                <div>Seguidores ({{ $this->followersCount() }})</div>
                <div>POSTS ({{ $this->postsCount() }})</div>
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
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Ranking</h3>
        <div class="flex flex-col gap-4">
            @foreach($topUsers as $rank)
                <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                    <div class="flex items-center gap-4">
                        {{-- <div class="w-12 h-12 rounded-full overflow-hidden">
                            <img src="{{ $rank->avatar }}" class="w-full h-full object-cover" />
                        </div> --}}
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $rank->name }}</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ '@' . $rank->username }}</p>
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                        {{ $rank->ranking_points }} pontos
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>