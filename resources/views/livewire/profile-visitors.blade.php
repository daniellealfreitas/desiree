<div class="bg-white dark:bg-zinc-800 shadow-sm rounded-lg overflow-hidden">
    <div class="p-4 sm:p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Meus Visitantes</h2>

            <div class="flex space-x-2">
                <!-- Filtro de data -->
                <select wire:model="filterDate" class="bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md text-sm">
                    <option value="all">Todos</option>
                    <option value="today">Hoje</option>
                    <option value="week">Esta semana</option>
                    <option value="month">Este mês</option>
                </select>

                <!-- Campo de busca simplificado -->
                <div class="relative">
                    <input
                        type="text"
                        wire:model="searchTerm"
                        placeholder="Buscar visitante..."
                        class="bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md pl-10 pr-4 py-2 text-sm w-full"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-flux::icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
                    </div>
                </div>
            </div>
        </div>

        @if($visitors->isEmpty())
            <div class="text-center py-8">
                <x-flux::icon name="user-group" class="h-12 w-12 mx-auto text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum visitante</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Ninguém visitou seu perfil ainda.
                </p>
            </div>
        @else
            <div class="overflow-hidden">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @foreach($visitors as $visit)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('user.profile', ['username' => $visit->visitor->username]) }}" target="_blank">
                                            <img
                                                class="h-10 w-10 rounded-full object-cover"
                                                src="{{ $visit->visitor->userPhotos->first() ? asset($visit->visitor->userPhotos->first()->photo_path) : asset('images/users/avatar.jpg') }}"
                                                alt="{{ $visit->visitor->name }}"
                                            >
                                        </a>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            <a href="{{ route('user.profile', ['username' => $visit->visitor->username]) }}" class="hover:underline" target="_blank">
                                                {{ $visit->visitor->name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            @if($visit->visited_at->isToday())
                                                Hoje às {{ $visit->visited_at->format('H:i') }}
                                            @elseif($visit->visited_at->isYesterday())
                                                Ontem às {{ $visit->visited_at->format('H:i') }}
                                            @else
                                                {{ $visit->visited_at->format('d/m/Y H:i') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <a
                                        href="{{ route('user.profile', ['username' => $visit->visitor->username]) }}"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        target="_blank"
                                    >
                                        Ver perfil
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="px-4 py-4 sm:px-6">
                {{ $visitors->links() }}
            </div>
        @endif
    </div>
</div>
