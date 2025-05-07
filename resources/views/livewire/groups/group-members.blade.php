<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Membros do Grupo</h2>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-flux::icon icon="magnifying-glass" class="h-5 w-5 text-gray-400" />
                </div>
                <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    placeholder="Buscar membros..."
                    class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                >
            </div>
        </div>

        <!-- Solicitações pendentes (apenas para administradores e moderadores) -->
        @if($canManage && $pendingMembers->isNotEmpty())
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Solicitações Pendentes</h3>

                <div class="space-y-4">
                    @foreach($pendingMembers as $pendingMember)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <a href="{{ route('user.profile', $pendingMember->username) }}" class="flex-shrink-0">
                                    <img
                                        src="{{ $pendingMember->userPhotos->first() ? asset('storage/' . $pendingMember->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                        alt="{{ $pendingMember->name }}"
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                </a>

                                <div class="ml-3">
                                    <a href="{{ route('user.profile', $pendingMember->username) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">
                                        {{ $pendingMember->name }}
                                    </a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Solicitou para entrar {{ $pendingMember->pivot->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <flux:button wire:click="rejectMember({{ $pendingMember->id }})" color="danger" size="sm">
                                    Recusar
                                </flux:button>

                                <flux:button wire:click="approveMember({{ $pendingMember->id }})" color="primary" size="sm">
                                    Aprovar
                                </flux:button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Lista de membros -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($members as $member)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center">
                        <a href="{{ route('user.profile', $member->username) }}" class="flex-shrink-0">
                            <img
                                src="{{ $member->userPhotos->first() ? asset('storage/' . $member->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                alt="{{ $member->name }}"
                                class="w-10 h-10 rounded-full object-cover"
                            >
                        </a>

                        <div class="ml-3">
                            <div class="flex items-center">
                                <a href="{{ route('user.profile', $member->username) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">
                                    {{ $member->name }}
                                </a>

                                @if($member->id === $group->creator_id)
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 rounded-full">
                                        Criador
                                    </span>
                                @elseif($member->pivot->role === 'admin')
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                        Admin
                                    </span>
                                @elseif($member->pivot->role === 'moderator')
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                        Moderador
                                    </span>
                                @endif
                            </div>

                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Membro desde {{ \Carbon\Carbon::parse($member->pivot->joined_at)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    @if($canManage && $member->id !== $group->creator_id && $member->id !== auth()->id())
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <x-flux::icon icon="ellipsis-vertical" class="w-5 h-5" />
                            </button>

                            <div
                                x-show="open"
                                @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-10"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                            >
                                @if(auth()->user()->isAdminOf($group))
                                    <button
                                        wire:click="openRoleModal({{ $member->id }})"
                                        @click="open = false"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600"
                                    >
                                        Alterar função
                                    </button>
                                @endif

                                <button
                                    wire:click="openRemoveModal({{ $member->id }})"
                                    @click="open = false"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600"
                                >
                                    Remover do grupo
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-3 p-8 text-center">
                    <flux:icon.users class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum membro encontrado</h3>
                    <p class="text-gray-500 dark:text-gray-400">Tente uma busca diferente ou convide novos membros.</p>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $members->links() }}
        </div>
    </div>

    <!-- Modal para alterar função -->
    <flux:modal wire:model="showRoleModal">
        <flux:modal.header>
            <flux:modal.title>Alterar Função do Membro</flux:modal.title>
        </flux:modal.header>

        <flux:modal.body>
            @if($selectedMember)
                <p class="mb-4">Alterar função de <strong>{{ $selectedMember->name }}</strong>:</p>

                <div class="space-y-2">
                    <div class="flex items-center">
                        <flux:radio id="role-member" wire:model="newRole" value="member" />
                        <flux:label for="role-member" value="Membro" class="ml-2" />
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Pode visualizar e criar postagens)
                        </span>
                    </div>

                    <div class="flex items-center">
                        <flux:radio id="role-moderator" wire:model="newRole" value="moderator" />
                        <flux:label for="role-moderator" value="Moderador" class="ml-2" />
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Pode moderar postagens e aprovar membros)
                        </span>
                    </div>

                    <div class="flex items-center">
                        <flux:radio id="role-admin" wire:model="newRole" value="admin" />
                        <flux:label for="role-admin" value="Administrador" class="ml-2" />
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Controle total sobre o grupo)
                        </span>
                    </div>
                </div>
            @endif
        </flux:modal.body>

        <flux:modal.footer>
            <flux:button wire:click="$set('showRoleModal', false)" color="secondary">
                Cancelar
            </flux:button>

            <flux:button wire:click="changeRole" color="primary">
                Salvar
            </flux:button>
        </flux:modal.footer>
    </flux:modal>

    <!-- Modal para remover membro -->
    <flux:modal wire:model="showRemoveModal">
        <flux:modal.header>
            <flux:modal.title>Remover Membro</flux:modal.title>
        </flux:modal.header>

        <flux:modal.body>
            @if($selectedMember)
                <p>Você tem certeza que deseja remover <strong>{{ $selectedMember->name }}</strong> do grupo?</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Esta ação não pode ser desfeita.</p>
            @endif
        </flux:modal.body>

        <flux:modal.footer>
            <flux:button wire:click="$set('showRemoveModal', false)" color="secondary">
                Cancelar
            </flux:button>

            <flux:button wire:click="removeMember" color="danger">
                Remover
            </flux:button>
        </flux:modal.footer>
    </flux:modal>
</div>
