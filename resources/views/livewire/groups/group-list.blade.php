<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Cabeçalho -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Grupos</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Encontre e participe de grupos com interesses em comum</p>
            </div>

            <div class="mt-4 md:mt-0">
                <flux:button href="{{ route('grupos.create') }}" color="primary">
                    <x-flux::icon icon="plus" class="w-5 h-5 mr-2" />
                    Criar Grupo
                </flux:button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                <!-- Busca -->
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-flux::icon icon="magnifying-glass" class="h-5 w-5 text-gray-400" />
                    </div>
                    <input
                        type="text"
                        wire:model.debounce.300ms="search"
                        placeholder="Buscar grupos..."
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>

                <!-- Filtro de associação -->
                <div class="w-full md:w-auto">
                    <select
                        wire:model="filter"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="all">Todos os grupos</option>
                        <option value="my">Meus grupos</option>
                        <option value="joined">Grupos que participo</option>
                        <option value="pending">Solicitações pendentes</option>
                        <option value="created">Grupos que criei</option>
                        <option value="not-joined">Grupos que não participo</option>
                    </select>
                </div>

                <!-- Filtro de privacidade -->
                <div class="w-full md:w-auto">
                    <select
                        wire:model="privacy"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="all">Qualquer privacidade</option>
                        <option value="public">Públicos</option>
                        <option value="private">Privados</option>
                        <option value="secret">Secretos</option>
                    </select>
                </div>

                <!-- Ordenação -->
                <div class="w-full md:w-auto">
                    <select
                        wire:model="sort"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="newest">Mais recentes</option>
                        <option value="oldest">Mais antigos</option>
                        <option value="popular">Mais populares</option>
                        <option value="alphabetical">Ordem alfabética</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Lista de grupos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($groups as $group)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:scale-105">
                    <!-- Imagem de capa -->
                    <div class="relative h-32 bg-gray-200 dark:bg-gray-700">
                        <img
                            src="{{ $group->cover_image ? asset('storage/' . $group->cover_image) : asset('images/default-group-cover.jpg') }}"
                            alt="{{ $group->name }}"
                            class="w-full h-full object-cover"
                        >

                        <!-- Badge de privacidade -->
                        <div class="absolute top-2 right-2">
                            @if($group->privacy === 'public')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <x-flux::icon icon="globe" class="w-3 h-3 mr-1" />
                                    Público
                                </span>
                            @elseif($group->privacy === 'private')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    <x-flux::icon icon="lock-closed" class="w-3 h-3 mr-1" />
                                    Privado
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    <x-flux::icon icon="eye-off" class="w-3 h-3 mr-1" />
                                    Secreto
                                </span>
                            @endif
                        </div>

                        <!-- Imagem do grupo -->
                        <div class="absolute -bottom-6 left-4">
                            <img
                                src="{{ $group->image ? asset('storage/' . $group->image) : asset('images/default-group.jpg') }}"
                                alt="{{ $group->name }}"
                                class="w-16 h-16 rounded-lg border-4 border-white dark:border-gray-800 object-cover"
                            >
                        </div>
                    </div>

                    <!-- Informações do grupo -->
                    <div class="pt-8 px-4 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                            <a href="{{ route('grupos.show', $group->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                {{ $group->name }}
                            </a>
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                            {{ $group->description ?: 'Sem descrição' }}
                        </p>

                        <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <x-flux::icon icon="users" class="w-4 h-4 mr-1" />
                            <span>{{ $group->members_count }} {{ $group->members_count == 1 ? 'membro' : 'membros' }}</span>
                        </div>
                    </div>

                    <!-- Rodapé com ações -->
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        @if(auth()->check() && auth()->user()->isMemberOf($group))
                            <flux:button href="{{ route('grupos.show', $group->slug) }}" color="secondary" class="w-full">
                                <x-flux::icon icon="login" class="w-4 h-4 mr-2" />
                                Acessar
                            </flux:button>
                        @else
                            <flux:button href="{{ route('grupos.show', $group->slug) }}" color="primary" class="w-full">
                                <x-flux::icon icon="eye" class="w-4 h-4 mr-2" />
                                Ver Detalhes
                            </flux:button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <x-flux::icon icon="user-group" class="w-16 h-16 text-gray-400 dark:text-gray-500 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum grupo encontrado</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 text-center max-w-md">
                        @if($search)
                            Não encontramos grupos com os critérios de busca especificados.
                        @else
                            Não há grupos disponíveis no momento.
                        @endif
                    </p>
                    <flux:button href="{{ route('grupos.create') }}" color="primary">
                        <x-flux::icon icon="plus" class="w-5 h-5 mr-2" />
                        Criar um Novo Grupo
                    </flux:button>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $groups->links() }}
        </div>
    </div>
</div>
