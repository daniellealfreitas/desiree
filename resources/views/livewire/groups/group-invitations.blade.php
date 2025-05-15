<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Convidar Pessoas</h2>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-flux::icon icon="magnifying-glass" class="h-5 w-5 text-gray-400" />
                </div>
                <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    placeholder="Buscar pessoas..."
                    class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                >
            </div>
        </div>

        <!-- Convites pendentes -->
        @if($pendingInvitations->isNotEmpty())
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Convites Pendentes</h3>

                <div class="space-y-4">
                    @foreach($pendingInvitations as $invitation)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <a href="{{ $invitation->user && $invitation->user->username ? route('user.profile', $invitation->user->username) : '#' }}" class="flex-shrink-0">
                                    <img
                                        src="{{ $invitation->user && $invitation->user->userPhotos->first() ? asset('storage/' . $invitation->user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                        alt="{{ $invitation->user ? $invitation->user->name : 'Usuário' }}"
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                </a>

                                <div class="ml-3">
                                    <a href="{{ $invitation->user && $invitation->user->username ? route('user.profile', $invitation->user->username) : '#' }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">
                                        {{ $invitation->user ? $invitation->user->name : 'Usuário não encontrado' }}
                                    </a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Convidado por {{ $invitation->inviter ? $invitation->inviter->name : 'Usuário' }} {{ $invitation->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            <flux:button wire:click="cancelInvitation({{ $invitation->id }})" color="secondary" size="sm">
                                Cancelar Convite
                            </flux:button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Lista de usuários para convidar -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($users as $user)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center">
                        <a href="{{ $user->username ? route('user.profile', $user->username) : '#' }}" class="flex-shrink-0">
                            <img
                                src="{{ $user->userPhotos->first() ? asset('storage/' . $user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                alt="{{ $user->name }}"
                                class="w-10 h-10 rounded-full object-cover"
                            >
                        </a>

                        <div class="ml-3">
                            <a href="{{ $user->username ? route('user.profile', $user->username) : '#' }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">
                                {{ $user->name }}
                            </a>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                @if($user->followers->count() > 0)
                                    {{ $user->followers->count() }} {{ $user->followers->count() == 1 ? 'seguidor' : 'seguidores' }}
                                @else
                                    Usuário
                                @endif
                            </div>
                        </div>
                    </div>

                    <flux:button wire:click="openInviteModal({{ $user->id }})" color="primary" size="sm">
                        Convidar
                    </flux:button>
                </div>
            @empty
                <div class="col-span-3 p-8 text-center">
                    <x-flux::icon icon="users" class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum usuário encontrado</h3>
                    <p class="text-gray-500 dark:text-gray-400">Tente uma busca diferente.</p>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal para confirmar convite -->
    <flux:modal wire:model="showInviteModal">
        <flux:modal.header>
            <flux:modal.title>Convidar para o Grupo</flux:modal.title>
        </flux:modal.header>

        <flux:modal.body>
            @if($selectedUser)
                <p>Você tem certeza que deseja convidar <strong>{{ $selectedUser->name }}</strong> para o grupo <strong>{{ $group->name }}</strong>?</p>

                @if($group->privacy === 'secret')
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <x-flux::icon icon="exclamation-circle" class="w-4 h-4 inline mr-1" />
                        Este é um grupo secreto. Ao convidar esta pessoa, você estará revelando a existência do grupo.
                    </p>
                @endif
            @else
                <p>Carregando informações do usuário...</p>
            @endif
        </flux:modal.body>

        <flux:modal.footer>
            <flux:button wire:click="$wire.set('showInviteModal', false)" color="secondary">
                Cancelar
            </flux:button>

            @if($selectedUser)
                <flux:button wire:click="invite" color="primary">
                    Convidar
                </flux:button>
            @endif
        </flux:modal.footer>
    </flux:modal>
</div>
