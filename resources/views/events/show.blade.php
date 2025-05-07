<x-layouts.app :title="$event->name">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Cabeçalho do evento com imagem de capa -->
        <div class="relative w-full h-64 md:h-96 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden mb-6">
            <img src="{{ $event->cover_image_url }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-2 py-1 bg-indigo-600 text-white text-xs font-medium rounded-full">
                                {{ $event->day_of_week }}
                            </span>
                            <span class="text-white text-sm">
                                {{ $event->formatted_date }}
                            </span>
                        </div>
                        
                        <h1 class="text-2xl md:text-3xl font-bold text-white">{{ $event->name }}</h1>
                        
                        <div class="flex items-center text-gray-200 text-sm mt-2">
                            <span class="flex items-center">
                                <x-flux::icon icon="clock" class="w-4 h-4 mr-1" />
                                {{ $event->formatted_start_time }} {{ $event->formatted_end_time ? ' - ' . $event->formatted_end_time : '' }}
                            </span>
                            
                            <span class="mx-2">•</span>
                            
                            <span class="flex items-center">
                                <x-flux::icon icon="map-pin" class="w-4 h-4 mr-1" />
                                {{ $event->location }}
                            </span>
                            
                            <span class="mx-2">•</span>
                            
                            <span class="flex items-center font-medium">
                                {{ $event->formatted_price }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-4 md:mt-0">
                        <flux:button href="{{ route('events.index') }}" color="secondary" size="sm">
                            <x-flux::icon icon="arrow-left" class="w-4 h-4 mr-1" />
                            Voltar para Eventos
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Detalhes do evento -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Descrição -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Sobre o Evento</h2>
                    
                    <div class="prose dark:prose-invert max-w-none">
                        {{ $event->description }}
                    </div>
                </div>
                
                <!-- Localização -->
                @if($event->address)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Localização</h2>
                        
                        <div class="flex items-start">
                            <x-flux::icon icon="map-pin" class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
                            <div>
                                <p class="text-gray-700 dark:text-gray-300 font-medium">{{ $event->location }}</p>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ $event->address }}
                                    @if($event->city || $event->state)
                                        , {{ $event->city }} {{ $event->state ? ' - ' . $event->state : '' }}
                                    @endif
                                    @if($event->zip_code)
                                        , {{ $event->zip_code }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-4 h-64 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <!-- Aqui você pode adicionar um mapa se desejar -->
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-gray-500 dark:text-gray-400">Mapa indisponível</span>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Organizador -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Organizado por</h2>
                    
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img 
                                src="{{ $event->creator->userPhotos->first() ? asset('storage/' . $event->creator->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}" 
                                alt="{{ $event->creator->name }}" 
                                class="w-12 h-12 rounded-full object-cover"
                            >
                        </div>
                        
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $event->creator->name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Administrador
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Inscrição e informações -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Componente de inscrição -->
                <livewire:events.event-registration :event="$event" />
                
                <!-- Compartilhar -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Compartilhar Evento</h2>
                    
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('events.show', $event->slug)) }}" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                            <x-flux::icon icon="facebook" class="w-5 h-5" />
                        </a>
                        
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('events.show', $event->slug)) }}&text={{ urlencode($event->name) }}" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-400 text-white hover:bg-blue-500 transition-colors">
                            <x-flux::icon icon="twitter" class="w-5 h-5" />
                        </a>
                        
                        <a href="https://wa.me/?text={{ urlencode($event->name . ' - ' . route('events.show', $event->slug)) }}" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white hover:bg-green-600 transition-colors">
                            <x-flux::icon icon="phone" class="w-5 h-5" />
                        </a>
                        
                        <button onclick="navigator.clipboard.writeText('{{ route('events.show', $event->slug) }}'); alert('Link copiado!');" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            <x-flux::icon icon="clipboard" class="w-5 h-5" />
                        </button>
                    </div>
                </div>
                
                <!-- Outros eventos -->
                @if($relatedEvents && $relatedEvents->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Outros Eventos</h2>
                        
                        <div class="space-y-4">
                            @foreach($relatedEvents as $relatedEvent)
                                <a href="{{ route('events.show', $relatedEvent->slug) }}" class="flex items-start hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded-lg transition-colors">
                                    <img src="{{ $relatedEvent->image_url }}" alt="{{ $relatedEvent->name }}" class="w-16 h-16 rounded-lg object-cover">
                                    
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $relatedEvent->name }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $relatedEvent->formatted_date }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
