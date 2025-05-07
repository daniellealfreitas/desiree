<x-layouts.app :title="__('Eventos')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Eventos</h1>
                <p class="mt-1 text-gray-500 dark:text-gray-400">Confira os próximos eventos e festas do Desiree Swing Club</p>
            </div>

            @if(auth()->check() && auth()->user()->isAdmin())
                <div class="mt-4 md:mt-0">
                    <x-flux::button href="{{ route('admin.events') }}" color="primary">
                        <x-flux::icon icon="cog-6-tooth" class="w-5 h-5 mr-2" />
                        Gerenciar Eventos
                    </x-flux::button>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calendário de eventos -->
            <div class="lg:col-span-2">
                <livewire:events.event-calendar />
            </div>

            <!-- Dias de evento -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 bg-indigo-600 text-white">
                        <h2 class="text-xl font-bold">Dias de Evento</h2>
                    </div>

                    <div class="p-4 space-y-4">
                        <div class="flex items-center p-3 rounded-lg bg-purple-50 dark:bg-purple-900/20">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-800 flex items-center justify-center mr-4">
                                <span class="text-purple-800 dark:text-purple-200 font-bold">QUA</span>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quarta-feira</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Noite de Swing</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 rounded-lg bg-pink-50 dark:bg-pink-900/20">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 dark:bg-pink-800 flex items-center justify-center mr-4">
                                <span class="text-pink-800 dark:text-pink-200 font-bold">SEX</span>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Sexta-feira</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Noite de Festa</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center mr-4">
                                <span class="text-blue-800 dark:text-blue-200 font-bold">SÁB</span>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Sábado</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Noite Premium</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Clique em um dia no calendário para ver os eventos programados.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de eventos -->
        <div class="mt-12">
            <livewire:events.event-list />
        </div>
    </div>
</x-layouts.app>
