<div>
    <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Gerenciamento de Usuários</h2>
        </div>

        <!-- Filtros e Busca -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar usuários..."
                icon="magnifying-glass"
            />

            <flux:select wire:model.live="roleFilter">
                <option value="">Todos os papéis</option>
                <option value="user">Usuário</option>
                <option value="admin">Administrador</option>
                <option value="moderator">Moderador</option>
            </flux:select>

            <flux:select wire:model.live="perPage">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </flux:select>
        </div>

        <!-- Tabela de Usuários -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('id')">
                            ID
                            @if($sortBy === 'id')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                            Nome
                            @if($sortBy === 'name')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('email')">
                            Email
                            @if($sortBy === 'email')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('role')">
                            Papel
                            @if($sortBy === 'role')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                            Cadastro
                            @if($sortBy === 'created_at')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
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
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
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
                                       ($user->role === 'moderator' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' :
                                       'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <flux:button wire:click="edit({{ $user->id }})" variant="outline" size="xs">
                                    <flux:icon name="pencil-square" class="h-4 w-4" />
                                </flux:button>
                                @if($user->id !== auth()->id())
                                    <flux:button wire:click="confirmDelete({{ $user->id }})" variant="outline" size="xs" class="ml-2">
                                        <flux:icon name="trash" class="h-4 w-4 text-red-500" />
                                    </flux:button>
                                @endif
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

    <!-- Modal de Edição -->
    <flux:modal wire:model="showModal" title="Editar Usuário">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <flux:input
                        wire:model="name"
                        label="Nome"
                        placeholder="Nome do usuário"
                        required
                    />
                </div>

                <div>
                    <flux:input
                        wire:model="email"
                        label="Email"
                        type="email"
                        placeholder="Email do usuário"
                        required
                    />
                </div>

                <div>
                    <flux:select
                        wire:model="role"
                        label="Papel"
                        required
                    >
                        <option value="user">Usuário</option>
                        <option value="moderator">Moderador</option>
                        <option value="admin">Administrador</option>
                    </flux:select>
                </div>

                <div>
                    <flux:checkbox
                        wire:model="active"
                        label="Usuário Ativo"
                    />
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <flux:button type="button" variant="outline" wire:click="$set('showModal', false)">
                    Cancelar
                </flux:button>
                <flux:button type="submit">
                    Atualizar
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Modal de Confirmação de Exclusão -->
    <flux:modal wire:model="confirmingDelete" title="Confirmar Exclusão">
        <div class="p-4">
            <p class="text-gray-700 dark:text-gray-300">
                Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.
            </p>

            <div class="mt-6 flex justify-end space-x-3">
                <flux:button variant="outline" wire:click="$set('confirmingDelete', false)">
                    Cancelar
                </flux:button>
                <flux:button variant="danger" wire:click="delete">
                    Excluir
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
