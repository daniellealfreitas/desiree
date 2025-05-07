<div>
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden">
        <!-- Cabeçalho com estatísticas -->
        <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold mb-4">Histórico de Pontuação</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalPoints) }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                </div>
                
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($dailyPoints) }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Hoje</div>
                </div>
                
                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($weeklyPoints) }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Semana</div>
                </div>
                
                <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($monthlyPoints) }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Mês</div>
                </div>
                
                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">#{{ $rankingPosition }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Ranking</div>
                </div>
            </div>
            
            <!-- Sequência de dias -->
            <div class="flex items-center mb-4">
                <div class="mr-2">
                    <x-flux::icon icon="calendar" class="w-5 h-5 text-orange-500" />
                </div>
                <div>
                    <span class="font-semibold">{{ $streakDays }} {{ $streakDays == 1 ? 'dia' : 'dias' }}</span> 
                    <span class="text-gray-600 dark:text-gray-400">consecutivos de atividade</span>
                </div>
            </div>
            
            <!-- Filtros -->
            <div class="flex flex-wrap gap-2 mt-4">
                <div class="mr-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Período:</label>
                    <div class="flex space-x-2">
                        <button wire:click="setPeriod('all')" class="px-3 py-1 text-sm rounded-full {{ $period === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                            Todos
                        </button>
                        <button wire:click="setPeriod('1 day')" class="px-3 py-1 text-sm rounded-full {{ $period === '1 day' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                            Hoje
                        </button>
                        <button wire:click="setPeriod('1 week')" class="px-3 py-1 text-sm rounded-full {{ $period === '1 week' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                            Semana
                        </button>
                        <button wire:click="setPeriod('1 month')" class="px-3 py-1 text-sm rounded-full {{ $period === '1 month' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                            Mês
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo:</label>
                    <div class="flex space-x-2">
                        <button wire:click="setActionType('all')" class="px-3 py-1 text-sm rounded-full {{ $actionType === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                            Todos
                        </button>
                        @foreach($actionTypes as $type)
                            <button wire:click="setActionType('{{ $type }}')" class="px-3 py-1 text-sm rounded-full {{ $actionType === $type ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                                {{ ucfirst($type) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lista de atividades -->
        <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
            @forelse($logs as $log)
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-{{ $log->color }}-100 dark:bg-{{ $log->color }}-900/30">
                            <x-flux::icon icon="{{ $log->icon }}" class="w-5 h-5 text-{{ $log->color }}-600 dark:text-{{ $log->color }}-400" />
                        </div>
                    </div>
                    
                    <div class="flex-grow">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">{{ $log->formatted_description }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $log->time_ago }}</p>
                            </div>
                            
                            <div class="text-lg font-bold {{ $log->points >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $log->points >= 0 ? '+' : '' }}{{ $log->points }}
                            </div>
                        </div>
                        
                        <div class="mt-2 flex justify-between items-center">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Total: {{ number_format($log->total_points) }} pontos
                            </div>
                            
                            @if($log->ranking_position)
                                <div class="text-sm bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">
                                    Posição #{{ $log->ranking_position }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    Nenhum registro de pontuação encontrado.
                </div>
            @endforelse
        </div>
        
        <!-- Paginação -->
        <div class="p-4 border-t border-neutral-200 dark:border-neutral-700">
            {{ $logs->links() }}
        </div>
    </div>
</div>
