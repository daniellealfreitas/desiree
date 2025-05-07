<div>
    <!-- Eventos em destaque -->
    @if(count($featuredEvents) > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Eventos em Destaque</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredEvents as $event)
                    <div class="group relative overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md transition-all hover:shadow-lg">
                        <div class="aspect-video w-full overflow-hidden">
                            <img 
                                src="{{ $event->image_url }}" 
                                alt="{{ $event->name }}" 
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                        </div>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-80"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                            <div class="mb-2 flex items-center space-x-2">
                                <span class="rounded-full bg-indigo-600 px-2 py-0.5 text-xs font-medium">
                                    {{ $event->day_of_week }}
                                </span>
                                <span class="text-sm">{{ $event->formatted_date }}</span>
                            </div>
                            
                            <h3 class="text-lg font-bold">{{ $event->name }}</h3>
                            
                            <div class="mt-2 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <x-flux::icon icon="map-pin" class="h-4 w-4" />
                                    <span class="text-sm">{{ $event->location }}</span>
                                </div>
                                
                                <span class="font-medium">{{ $event->formatted_price }}</span>
                            </div>
                            
                            <flux:button href="{{ route('events.show', $event->slug) }}" color="primary" class="mt-3 w-full">
                                Ver Detalhes
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    <!-- Filtros e busca -->
    <div class="mb-6 flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Buscar eventos..." 
                class="w-full"
                icon="magnifying-glass"
            />
        </div>
        
        <div class="flex gap-2">
            <flux:select wire:model.live="filter" class="w-full">
                <option value="upcoming">Próximos eventos</option>
                <option value="this-week">Esta semana</option>
                <option value="this-month">Este mês</option>
                <option value="past">Eventos passados</option>
            </flux:select>
            
            <flux:select wire:model.live="dayFilter" class="w-full">
                <option value="all">Todos os dias</option>
                <option value="event-days">Dias de evento</option>
                <option value="wednesday">Quarta-feira</option>
                <option value="friday">Sexta-feira</option>
                <option value="saturday">Sábado</option>
            </flux:select>
        </div>
    </div>
    
    <!-- Lista de eventos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="relative">
                    <img 
                        src="{{ $event->image_url }}" 
                        alt="{{ $event->name }}" 
                        class="w-full h-48 object-cover"
                    >
                    
                    <div class="absolute top-0 right-0 p-2">
                        <span class="inline-flex items-center rounded-full bg-indigo-600 px-2.5 py-0.5 text-xs font-medium text-white">
                            {{ $event->day_of_week }}
                        </span>
                    </div>
                    
                    @if($event->is_sold_out)
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <span class="bg-red-600 text-white px-4 py-2 rounded-full font-bold transform rotate-12">
                                ESGOTADO
                            </span>
                        </div>
                    @elseif($event->has_passed)
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <span class="bg-gray-600 text-white px-4 py-2 rounded-full font-bold transform rotate-12">
                                ENCERRADO
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $event->name }}</h3>
                        <span class="text-indigo-600 dark:text-indigo-400 font-medium">{{ $event->formatted_price }}</span>
                    </div>
                    
                    <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center mb-1">
                            <x-flux::icon icon="calendar" class="w-4 h-4 mr-2" />
                            <span>{{ $event->formatted_date }} às {{ $event->formatted_start_time }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <x-flux::icon icon="map-pin" class="w-4 h-4 mr-2" />
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        {{ Str::limit($event->description, 100) }}
                    </p>
                    
                    <div class="flex justify-between items-center">
                        @if($event->capacity)
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $event->available_spots }} vagas disponíveis
                            </span>
                        @else
                            <span></span>
                        @endif
                        
                        <flux:button href="{{ route('events.show', $event->slug) }}" color="primary">
                            Ver Detalhes
                        </flux:button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
                <x-flux::icon icon="calendar" class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum evento encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400">Tente uma busca diferente ou verifique novamente mais tarde.</p>
            </div>
        @endforelse
    </div>
    
    <!-- Paginação -->
    <div class="mt-6">
        {{ $events->links() }}
    </div>
</div>
