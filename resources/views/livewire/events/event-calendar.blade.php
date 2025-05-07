<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
    <!-- Cabeçalho do calendário -->
    <div class="p-4 bg-indigo-600 text-white flex items-center justify-between">
        <button wire:click="previousMonth" class="p-2 rounded-full hover:bg-indigo-700 transition-colors">
            <x-flux::icon icon="chevron-left" class="w-5 h-5" />
        </button>
        
        <h2 class="text-xl font-bold">
            @php
                $monthNames = [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                ];
            @endphp
            {{ $monthNames[$currentMonth] }} {{ $currentYear }}
        </h2>
        
        <button wire:click="nextMonth" class="p-2 rounded-full hover:bg-indigo-700 transition-colors">
            <x-flux::icon icon="chevron-right" class="w-5 h-5" />
        </button>
    </div>
    
    <!-- Dias da semana -->
    <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800">Seg</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800">Ter</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 bg-purple-50 dark:bg-purple-900/20">Qua</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800">Qui</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 bg-pink-50 dark:bg-pink-900/20">Sex</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 bg-blue-50 dark:bg-blue-900/20">Sáb</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800">Dom</div>
    </div>
    
    <!-- Dias do mês -->
    <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
        @foreach($weeks as $week)
            @foreach($week as $day)
                @php
                    $hasEvents = isset($monthEvents[$day['date']]) && count($monthEvents[$day['date']]) > 0;
                    $isSelected = $selectedDate === $day['date'];
                    
                    // Definir classes base
                    $dayClasses = 'min-h-[100px] p-2 bg-white dark:bg-gray-800 flex flex-col';
                    
                    // Adicionar classes para dias especiais
                    if ($day['isWednesday']) {
                        $dayClasses .= ' bg-purple-50 dark:bg-purple-900/20';
                    } elseif ($day['isFriday']) {
                        $dayClasses .= ' bg-pink-50 dark:bg-pink-900/20';
                    } elseif ($day['isSaturday']) {
                        $dayClasses .= ' bg-blue-50 dark:bg-blue-900/20';
                    }
                    
                    // Adicionar classes para dias de outros meses
                    if (!$day['isCurrentMonth']) {
                        $dayClasses .= ' opacity-50';
                    }
                    
                    // Adicionar classes para o dia atual
                    if ($day['isToday']) {
                        $dayClasses .= ' border-2 border-indigo-500';
                    }
                    
                    // Adicionar classes para o dia selecionado
                    if ($isSelected) {
                        $dayClasses .= ' bg-indigo-50 dark:bg-indigo-900/20';
                    }
                @endphp
                
                <div 
                    wire:click="selectDate('{{ $day['date'] }}')" 
                    class="{{ $dayClasses }}"
                >
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium {{ $day['isToday'] ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $day['day'] }}
                        </span>
                        
                        @if($hasEvents)
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-xs text-white">
                                {{ count($monthEvents[$day['date']]) }}
                            </span>
                        @endif
                    </div>
                    
                    @if($hasEvents)
                        <div class="space-y-1 overflow-y-auto flex-1">
                            @foreach($monthEvents[$day['date']] as $event)
                                <div class="text-xs p-1 rounded bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-200 truncate">
                                    {{ $event->formatted_start_time }} - {{ $event->name }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        @endforeach
    </div>
    
    <!-- Eventos do dia selecionado -->
    @if($selectedDate && count($selectedDateEvents) > 0)
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Eventos em {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
            </h3>
            
            <div class="space-y-4">
                @foreach($selectedDateEvents as $event)
                    <div class="flex items-start p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <div class="flex-shrink-0 h-12 w-12 mr-4">
                            <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="h-12 w-12 rounded-lg object-cover">
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $event->name }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $event->formatted_start_time }} - {{ $event->formatted_end_time }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $event->location }}
                            </p>
                        </div>
                        
                        <div class="ml-4">
                            <flux:button href="{{ route('events.show', $event->slug) }}" color="primary" size="xs">
                                Ver Detalhes
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
