<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Inscrição no Evento</h2>

        @if(!auth()->check())
            <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-flux::icon icon="exclamation-triangle" class="h-5 w-5 text-yellow-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                            Você precisa estar logado para se inscrever neste evento.
                        </p>
                        <div class="mt-2">
                            <x-flux::button href="{{ route('login') }}" color="primary" size="sm">
                                Fazer Login
                            </x-flux::button>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($event->has_passed)
            <div class="bg-gray-50 dark:bg-gray-700 border-l-4 border-gray-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-flux::icon icon="clock" class="h-5 w-5 text-gray-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700 dark:text-gray-200">
                            Este evento já ocorreu e não está mais aceitando inscrições.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($event->is_sold_out)
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-flux::icon icon="x-circle" class="h-5 w-5 text-red-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 dark:text-red-200">
                            Este evento está esgotado e não está mais aceitando inscrições.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($isRegistered)
            <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-flux::icon icon="check-circle" class="h-5 w-5 text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-200">
                            Você já está inscrito neste evento.
                        </p>

                        @if($attendee && $attendee->ticket_code)
                            <p class="text-sm text-green-700 dark:text-green-200 mt-2">
                                Seu código de ingresso: <span class="font-bold">{{ $attendee->ticket_code }}</span>
                            </p>
                        @endif

                        <div class="mt-2">
                            <x-flux::button wire:click="confirmCancel" color="danger" size="sm">
                                Cancelar Inscrição
                            </x-flux::button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-indigo-50 dark:bg-indigo-900/30 border-l-4 border-indigo-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-flux::icon icon="ticket" class="h-5 w-5 text-indigo-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-indigo-700 dark:text-indigo-200">
                            Inscreva-se neste evento para garantir sua vaga!
                        </p>

                        @if($event->capacity)
                            <p class="text-sm text-indigo-700 dark:text-indigo-200 mt-1">
                                {{ $event->available_spots }} vagas disponíveis de {{ $event->capacity }}.
                            </p>
                        @endif

                        <div class="mt-2">
                            <x-flux::button wire:click="confirmRegistration" color="primary" size="sm">
                                {{ $event->is_free ? 'Inscrever-se Gratuitamente' : 'Inscrever-se por ' . $event->formatted_price }}
                            </x-flux::button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Informações do Evento</h3>

            <div class="space-y-3">
                <div class="flex items-start">
                    <x-flux::icon icon="calendar" class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
                    <div>
                        <p class="text-gray-700 dark:text-gray-300">
                            <span class="font-medium">Data:</span> {{ $event->formatted_date }} ({{ $event->day_of_week }})
                        </p>
                        <p class="text-gray-700 dark:text-gray-300">
                            <span class="font-medium">Horário:</span> {{ $event->formatted_start_time }} {{ $event->formatted_end_time ? ' - ' . $event->formatted_end_time : '' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start">
                    <x-flux::icon icon="map-pin" class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
                    <div>
                        <p class="text-gray-700 dark:text-gray-300">
                            <span class="font-medium">Local:</span> {{ $event->location }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start">
                    <x-flux::icon icon="currency-dollar" class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
                    <div>
                        <p class="text-gray-700 dark:text-gray-300">
                            <span class="font-medium">Preço:</span> {{ $event->formatted_price }}
                        </p>
                    </div>
                </div>

                @if($event->capacity)
                    <div class="flex items-start">
                        <x-flux::icon icon="users" class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
                        <div>
                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="font-medium">Capacidade:</span> {{ $event->capacity }} pessoas
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="font-medium">Vagas disponíveis:</span> {{ $event->available_spots }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de confirmação de inscrição -->
    <x-flux::modal wire:model="showConfirmModal">
        <x-flux::modal.header>
            <x-flux::modal.title>Confirmar Inscrição</x-flux::modal.title>
        </x-flux::modal.header>

        <x-flux::modal.body>
            <p class="mb-4">Você está prestes a se inscrever no evento:</p>

            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                <h3 class="font-bold text-gray-900 dark:text-white">{{ $event->name }}</h3>
                <p class="text-gray-600 dark:text-gray-300">{{ $event->formatted_date }} às {{ $event->formatted_start_time }}</p>
                <p class="text-gray-600 dark:text-gray-300">{{ $event->location }}</p>
                <p class="font-medium text-indigo-600 dark:text-indigo-400 mt-2">{{ $event->formatted_price }}</p>
            </div>

            @if(!$event->is_free)
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Ao clicar em "Confirmar", você será redirecionado para a página de pagamento do Stripe.
                </p>
            @endif
        </x-flux::modal.body>

        <x-flux::modal.footer>
            <x-flux::button wire:click="$set('showConfirmModal', false)" color="secondary">
                Cancelar
            </x-flux::button>

            <x-flux::button wire:click="register" color="primary">
                Confirmar
            </x-flux::button>
        </x-flux::modal.footer>
    </x-flux::modal>

    <!-- Modal de confirmação de cancelamento -->
    <x-flux::modal wire:model="showCancelModal">
        <x-flux::modal.header>
            <x-flux::modal.title>Cancelar Inscrição</x-flux::modal.title>
        </x-flux::modal.header>

        <x-flux::modal.body>
            <p>Tem certeza que deseja cancelar sua inscrição no evento <strong>{{ $event->name }}</strong>?</p>

            @if(!$event->is_free && $attendee && $attendee->payment_status === 'completed')
                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                        <x-flux::icon icon="exclamation-triangle" class="h-5 w-5 text-yellow-400 inline-block mr-1" />
                        Atenção: O valor pago não será reembolsado automaticamente. Entre em contato com a administração para solicitar reembolso.
                    </p>
                </div>
            @endif
        </x-flux::modal.body>

        <x-flux::modal.footer>
            <x-flux::button wire:click="$set('showCancelModal', false)" color="secondary">
                Voltar
            </x-flux::button>

            <x-flux::button wire:click="cancelRegistration" color="danger">
                Cancelar Inscrição
            </x-flux::button>
        </x-flux::modal.footer>
    </x-flux::modal>
</div>
