<div>
    <!-- Modal de Busca -->
    <div
        x-data="{ 
            open: @entangle('isOpen'),
            focusSearch() {
                setTimeout(() => {
                    $refs.searchInput.focus();
                }, 100);
            }
        }"
        x-show="open"
        x-on:open-search-modal.window="open = true; focusSearch()"
        x-on:keydown.escape.window="open = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div 
            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
        ></div>

        <!-- Modal Content -->
        <div class="flex items-start justify-center min-h-screen pt-16 px-4 pb-20 text-center sm:block sm:p-0">
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                @click.away="open = false"
            >
                <!-- Search Input -->
                <div class="p-4 border-b border-gray-200 dark:border-zinc-700">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-flux::icon name="magnifying-glass" class="h-5 w-5 text-gray-400" />
                        </div>
                        <input
                            x-ref="searchInput"
                            type="text"
                            wire:model.live.debounce.300ms="searchTerm"
                            placeholder="Buscar perfis e páginas..."
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 dark:border-zinc-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-zinc-700 dark:text-white"
                            @keydown.escape.stop="open = false"
                        >
                    </div>
                </div>

                <!-- Search Results -->
                <div class="max-h-[60vh] overflow-y-auto p-4">
                    <!-- Loading indicator -->
                    <div wire:loading wire:target="searchTerm" class="flex justify-center py-4">
                        <x-flux::icon name="arrow-path" class="w-6 h-6 text-gray-400 animate-spin" />
                    </div>

                    <div wire:loading.remove wire:target="searchTerm">
                        <!-- Empty state -->
                        @if(empty($searchTerm) || strlen($searchTerm) < 2)
                            <div class="text-center py-8">
                                <x-flux::icon name="magnifying-glass" class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                                <p class="text-gray-500 dark:text-gray-400">Digite pelo menos 2 caracteres para buscar</p>
                            </div>
                        @elseif(empty($searchResults['users']) && empty($searchResults['pages']))
                            <div class="text-center py-8">
                                <x-flux::icon name="face-frown" class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                                <p class="text-gray-500 dark:text-gray-400">Nenhum resultado encontrado para "{{ $searchTerm }}"</p>
                            </div>
                        @else
                            <!-- Users section -->
                            @if(!empty($searchResults['users']))
                                <div class="mb-6">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Perfis</h3>
                                    <ul class="space-y-2">
                                        @foreach($searchResults['users'] as $user)
                                            <li>
                                                <a 
                                                    href="{{ $user['url'] }}" 
                                                    class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-zinc-700 rounded-lg"
                                                    wire:navigate
                                                    @click="open = false"
                                                >
                                                    <img 
                                                        src="{{ $user['photo'] }}" 
                                                        alt="{{ $user['name'] }}" 
                                                        class="w-10 h-10 rounded-full object-cover mr-3"
                                                    >
                                                    <div>
                                                        <p class="font-medium text-gray-900 dark:text-white">{{ $user['name'] }}</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">@{{ $user['username'] }}</p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Pages section -->
                            @if(!empty($searchResults['pages']))
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Páginas</h3>
                                    <ul class="space-y-2">
                                        @foreach($searchResults['pages'] as $page)
                                            <li>
                                                <a 
                                                    href="{{ $page['url'] }}" 
                                                    class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-zinc-700 rounded-lg"
                                                    wire:navigate
                                                    @click="open = false"
                                                >
                                                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center mr-3">
                                                        <x-flux::icon name="{{ $page['icon'] }}" class="w-5 h-5 text-red-500 dark:text-red-300" />
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900 dark:text-white">{{ $page['name'] }}</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $page['description'] }}</p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 dark:bg-zinc-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-zinc-700">
                    <button 
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="open = false"
                    >
                        Fechar
                    </button>
                    <a 
                        href="{{ route('busca') }}" 
                        class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-zinc-600 shadow-sm px-4 py-2 bg-white dark:bg-zinc-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:navigate
                        @click="open = false"
                    >
                        Busca avançada
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
