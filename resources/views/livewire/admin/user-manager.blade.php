<div>
    <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Gerenciamento de Usuários</h2>

            <x-flux::button wire:click="$dispatch('openModal', ['create'])" color="primary">
                <x-flux::icon icon="plus" class="w-5 h-5 mr-2" />
                Novo Usuário
            </x-flux::button>
        </div>

        <!-- Filtros e Busca -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <x-flux::input
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar usuários..."
                icon="magnifying-glass"
            />

            <x-flux::select wire:model.live="roleFilter">
                <option value="">Todos os papéis</option>
                <option value="visitante">Visitante</option>
                <option value="vip">VIP</option>
                <option value="admin">Administrador</option>
            </x-flux::select>

            <x-flux::select wire:model.live="perPage">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </x-flux::select>
        </div>

        <!-- Tabela de Usuários -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('id')">
                            ID
                            @if($sortBy === 'id')
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                            Nome
                            @if($sortBy === 'name')
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('email')">
                            Email
                            @if($sortBy === 'email')
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('role')">
                            Papel
                            @if($sortBy === 'role')
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                            Cadastro
                            @if($sortBy === 'created_at')
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->userPhotos->count() > 0)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($user->userPhotos->first()->photo_path) }}" alt="{{ $user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <x-flux::icon name="user" class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->username }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $user->role === 'admin' ? 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200' :
                                       ($user->role === 'vip' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' :
                                       'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <x-flux::button
                                        wire:click="$dispatch('openModal', ['edit', { userId: {{ $user->id }} }])"
                                        color="secondary"
                                        size="xs"
                                    >
                                        <x-flux::icon icon="pencil" class="w-4 h-4" />
                                    </x-flux::button>

                                    @if($user->id !== auth()->id())
                                        <x-flux::button
                                            wire:click="$dispatch('openModal', ['delete', { userId: {{ $user->id }} }])"
                                            color="danger"
                                            size="xs"
                                        >
                                            <x-flux::icon icon="trash" class="w-4 h-4" />
                                        </x-flux::button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal de Criação/Edição -->
    <div
        x-data="{ open: false }"
        x-init="
            window.addEventListener('openModal', event => {
                if (event.detail[0] === 'edit') {
                    open = true;
                    $wire.edit(event.detail[1].userId);
                } else if (event.detail[0] === 'create') {
                    open = true;
                    $wire.createUser();
                }
            });
        "
    >
        <div
            x-show="open"
            x-cloak
            @keydown.escape.window="open = false"
            class="fixed inset-0 z-50 overflow-y-auto"
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
            <div class="flex items-center justify-center min-h-screen p-4">
                <div
                    class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-md w-full p-6 relative mx-auto"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    @click.away="open = false"
                >
                    <!-- Header -->
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ $isEditing ? 'Editar Usuário' : 'Novo Usuário' }}
                        </h3>
                    </div>

                    <!-- Body -->
                    <form wire:submit.prevent="save" class="space-y-4" x-on:saved.window="open = false">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-flux::input
                                    wire:model.live="name"
                                    label="Nome"
                                    placeholder="Nome do usuário"
                                    required
                                />
                            </div>

                            <div>
                                <x-flux::input
                                    wire:model.live="email"
                                    label="Email"
                                    type="email"
                                    placeholder="Email do usuário"
                                    required
                                />
                            </div>

                            <div>
                                <x-flux::select
                                    wire:model.live="role"
                                    label="Papel"
                                    required
                                >
                                    <option value="visitante">Visitante</option>
                                    <option value="vip">VIP</option>
                                    <option value="admin">Administrador</option>
                                </x-flux::select>
                            </div>

                            <div>
                                <x-flux::checkbox
                                    wire:model.live="active"
                                    label="Usuário Ativo"
                                />
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <button
                                type="button"
                                @click="open = false"
                                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                {{ $isEditing ? 'Salvar Alterações' : 'Criar Usuário' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div
        x-data="{ open: false }"
        x-init="
            window.addEventListener('openModal', event => {
                if (event.detail[0] === 'delete') {
                    open = true;
                    $wire.confirmDelete(event.detail[1].userId);
                }
            });
        "
    >
        <div
            x-show="open"
            x-cloak
            @keydown.escape.window="open = false"
            class="fixed inset-0 z-50 overflow-y-auto"
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
            <div class="flex items-center justify-center min-h-screen p-4">
                <div
                    class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-md w-full p-6 relative mx-auto"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    @click.away="open = false"
                >
                    <!-- Header -->
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Confirmar Exclusão
                        </h3>
                    </div>

                    <!-- Body -->
                    <div class="mb-4">
                        <p class="text-gray-700 dark:text-gray-300">
                            Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            @click="open = false"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        >
                            Cancelar
                        </button>
                        <button
                            type="button"
                            @click="$wire.delete().then(() => { open = false })"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
