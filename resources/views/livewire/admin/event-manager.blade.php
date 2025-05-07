<div>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Gerenciamento de Eventos</h2>

            <x-flux::button wire:click="createEvent" color="primary">
                <x-flux::icon icon="plus" class="w-5 h-5 mr-2" />
                Novo Evento
            </x-flux::button>
        </div>

        <!-- Filtros -->
        <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex-1 min-w-[200px]">
                <x-flux::input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar eventos..."
                    class="w-full"
                    icon="magnifying-glass"
                />
            </div>

            <div class="flex gap-2">
                <x-flux::select wire:model.live="status" class="w-full">
                    <option value="">Todos os status</option>
                    <option value="active">Ativos</option>
                    <option value="inactive">Inativos</option>
                </x-flux::select>

                <x-flux::select wire:model.live="dateFilter" class="w-full">
                    <option value="">Todas as datas</option>
                    <option value="upcoming">Próximos</option>
                    <option value="past">Passados</option>
                    <option value="today">Hoje</option>
                    <option value="this-week">Esta semana</option>
                    <option value="this-month">Este mês</option>
                </x-flux::select>
            </div>
        </div>

        <!-- Tabela de eventos -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Evento
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Data
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Preço
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Inscritos
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($events as $event)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-lg object-cover" src="{{ $event->image_url }}" alt="{{ $event->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $event->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($event->location, 30) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $event->formatted_date }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $event->formatted_start_time }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $event->formatted_price }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $event->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                    {{ $event->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                                @if($event->is_featured)
                                    <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        Destaque
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $event->attendees->count() }}
                                @if($event->capacity)
                                    / {{ $event->capacity }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <x-flux::button wire:click="viewAttendees({{ $event->id }})" color="secondary" size="xs">
                                        <x-flux::icon icon="users" class="w-4 h-4" />
                                    </x-flux::button>

                                    <x-flux::button href="{{ route('events.show', $event->slug) }}" color="secondary" size="xs">
                                        <x-flux::icon icon="eye" class="w-4 h-4" />
                                    </x-flux::button>

                                    <x-flux::button wire:click="editEvent({{ $event->id }})" color="secondary" size="xs">
                                        <x-flux::icon icon="pencil" class="w-4 h-4" />
                                    </x-flux::button>

                                    <x-flux::button wire:click="confirmDelete({{ $event->id }})" color="danger" size="xs">
                                        <x-flux::icon icon="trash" class="w-4 h-4" />
                                    </x-flux::button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                Nenhum evento encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>

    <!-- Modal de criação/edição de evento -->
    <x-flux::modal wire:model="showEventModal" size="xl">
        <x-flux::modal.header>
            <x-flux::modal.title>{{ $editMode ? 'Editar Evento' : 'Novo Evento' }}</x-flux::modal.title>
        </x-flux::modal.header>

        <x-flux::modal.body>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome e descrição -->
                <div class="md:col-span-2">
                    <x-flux::label for="name" value="Nome do Evento" />
                    <x-flux::input id="name" wire:model="name" class="w-full" />
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <x-flux::label for="description" value="Descrição" />
                    <x-flux::textarea id="description" wire:model="description" rows="4" class="w-full" />
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Data e hora -->
                <div>
                    <x-flux::label for="date" value="Data" />
                    <x-flux::input id="date" type="date" wire:model="date" class="w-full" />
                    @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-flux::label for="start_time" value="Hora de Início" />
                        <x-flux::input id="start_time" type="time" wire:model="start_time" class="w-full" />
                        @error('start_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-flux::label for="end_time" value="Hora de Término" />
                        <x-flux::input id="end_time" type="time" wire:model="end_time" class="w-full" />
                        @error('end_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Preço e capacidade -->
                <div>
                    <x-flux::label for="price" value="Preço (R$)" />
                    <x-flux::input id="price" type="number" step="0.01" wire:model="price" class="w-full" />
                    @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-flux::label for="capacity" value="Capacidade" />
                    <x-flux::input id="capacity" type="number" wire:model="capacity" class="w-full" />
                    @error('capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Local -->
                <div>
                    <x-flux::label for="location" value="Local" />
                    <x-flux::input id="location" wire:model="location" class="w-full" />
                    @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-flux::label for="address" value="Endereço" />
                    <x-flux::input id="address" wire:model="address" class="w-full" />
                    @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-flux::label for="city" value="Cidade" />
                    <x-flux::input id="city" wire:model="city" class="w-full" />
                    @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-flux::label for="state" value="Estado" />
                        <x-flux::input id="state" wire:model="state" class="w-full" />
                        @error('state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-flux::label for="zip_code" value="CEP" />
                        <x-flux::input id="zip_code" wire:model="zip_code" class="w-full" />
                        @error('zip_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Imagens -->
                <div>
                    <x-flux::label for="image" value="Imagem Principal" />
                    <div class="mt-2">
                        @if($temp_image)
                            <div class="mb-2">
                                <img src="{{ $temp_image }}" class="h-32 w-auto rounded-lg object-cover">
                            </div>
                        @endif
                        <input type="file" wire:model="image" id="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    </div>
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-flux::label for="cover_image" value="Imagem de Capa" />
                    <div class="mt-2">
                        @if($temp_cover_image)
                            <div class="mb-2">
                                <img src="{{ $temp_cover_image }}" class="h-32 w-auto rounded-lg object-cover">
                            </div>
                        @endif
                        <input type="file" wire:model="cover_image" id="cover_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    </div>
                    @error('cover_image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Opções -->
                <div>
                    <x-flux::checkbox id="is_featured" wire:model="is_featured" />
                    <x-flux::label for="is_featured" value="Evento em destaque" class="ml-2" />
                    @error('is_featured') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-flux::checkbox id="is_active" wire:model="is_active" />
                    <x-flux::label for="is_active" value="Evento ativo" class="ml-2" />
                    @error('is_active') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-flux::modal.body>

        <x-flux::modal.footer>
            <x-flux::button wire:click="$set('showEventModal', false)" color="secondary">
                Cancelar
            </x-flux::button>

            <x-flux::button wire:click="saveEvent" color="primary">
                {{ $editMode ? 'Salvar Alterações' : 'Criar Evento' }}
            </x-flux::button>
        </x-flux::modal.footer>
    </x-flux::modal>

    <!-- Modal de confirmação de exclusão -->
    <x-flux::modal wire:model="showDeleteModal">
        <x-flux::modal.header>
            <x-flux::modal.title>Confirmar Exclusão</x-flux::modal.title>
        </x-flux::modal.header>

        <x-flux::modal.body>
            <p>Tem certeza que deseja excluir este evento? Esta ação não pode ser desfeita.</p>
        </x-flux::modal.body>

        <x-flux::modal.footer>
            <x-flux::button wire:click="$set('showDeleteModal', false)" color="secondary">
                Cancelar
            </x-flux::button>

            <x-flux::button wire:click="deleteEvent" color="danger">
                Excluir
            </x-flux::button>
        </x-flux::modal.footer>
    </x-flux::modal>

    <!-- Modal de participantes -->
    <x-flux::modal wire:model="showAttendeeModal" size="lg">
        <x-flux::modal.header>
            <x-flux::modal.title>Participantes do Evento</x-flux::modal.title>
        </x-flux::modal.header>

        <x-flux::modal.body>
            @if($selectedEvent)
                <h3 class="text-lg font-medium mb-4">{{ $selectedEvent->name }} - {{ $selectedEvent->formatted_date }}</h3>

                @if(count($attendees) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Participante
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Pagamento
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Código
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($attendees as $attendee)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $attendee->user->userPhotos->first() ? asset('storage/' . $attendee->user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}" alt="{{ $attendee->user->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $attendee->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $attendee->user->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $attendee->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                                {{ $attendee->status === 'registered' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                                {{ $attendee->status === 'attended' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : '' }}
                                                {{ $attendee->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                            ">
                                                {{ ucfirst($attendee->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $attendee->payment_status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                                {{ $attendee->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                                {{ $attendee->payment_status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                                {{ $attendee->payment_status === 'refunded' ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' : '' }}
                                            ">
                                                {{ ucfirst($attendee->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $attendee->ticket_code ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($attendee->status === 'confirmed' && !$attendee->checked_in_at)
                                                <x-flux::button wire:click="checkInAttendee({{ $attendee->id }})" color="primary" size="xs">
                                                    Check-in
                                                </x-flux::button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Nenhum participante registrado para este evento.</p>
                @endif
            @endif
        </x-flux::modal.body>

        <x-flux::modal.footer>
            <x-flux::button wire:click="$set('showAttendeeModal', false)" color="secondary">
                Fechar
            </x-flux::button>
        </x-flux::modal.footer>
    </x-flux::modal>
</div>
