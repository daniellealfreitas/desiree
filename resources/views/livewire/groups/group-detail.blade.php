<div>
    <!-- Cabeçalho do grupo com imagem de capa -->
    <div class="relative w-full h-64 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden mb-6">
        <img src="{{ $group->cover_image_url }}" alt="{{ $group->name }}" class="w-full h-full object-cover">

        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
            <div class="flex items-end space-x-4">
                <div class="relative">
                    <img src="{{ $group->image_url }}" alt="{{ $group->name }}" class="w-24 h-24 rounded-lg border-4 border-white dark:border-gray-800 object-cover">
                </div>

                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-white">{{ $group->name }}</h1>
                    <div class="flex items-center text-gray-200 text-sm mt-1">
                        <span class="flex items-center">
                            <x-flux::icon icon="users" class="w-4 h-4 mr-1" />
                            {{ $group->members_count }} {{ $group->members_count == 1 ? 'membro' : 'membros' }}
                        </span>

                        <span class="mx-2">•</span>

                        <span class="flex items-center">
                            @if($group->privacy === 'public')

                                Público
                            @elseif($group->privacy === 'private')
                                <x-flux::icon icon="lock-closed" class="w-4 h-4 mr-1" />
                                Privado
                            @else
                                <x-flux::icon icon="eye-off" class="w-4 h-4 mr-1" />
                                Secreto
                            @endif
                        </span>

                        <span class="mx-2">•</span>

                        <span class="flex items-center">
                            <x-flux::icon icon="calendar" class="w-4 h-4 mr-1" />
                            Criado {{ $group->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    @if($isMember)
                        <flux:button color="secondary" size="sm" wire:click="confirmLeave">
                            Sair do Grupo
                        </flux:button>
                    @else
                        <flux:button color="primary" size="sm" wire:click="confirmJoin">
                            Entrar no Grupo
                        </flux:button>
                    @endif

                    @if($isAdmin)
                        <flux:button href="{{ route('grupos.edit', $group) }}" color="secondary" size="sm">
                            <x-flux::icon icon="pencil" class="w-4 h-4 mr-1" />
                            Editar
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Descrição do grupo -->
    @if($group->description)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
            <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Sobre este grupo</h2>
            <p class="text-gray-700 dark:text-gray-300">{{ $group->description }}</p>
        </div>
    @endif

    <!-- Navegação por abas -->
    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
        <nav class="flex space-x-8">
            <button
                wire:click="changeTab('posts')"
                class="py-4 px-1 border-b-2 font-medium text-sm {{ $tab === 'posts' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
            >
                Postagens
            </button>

            <button
                wire:click="changeTab('members')"
                class="py-4 px-1 border-b-2 font-medium text-sm {{ $tab === 'members' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
            >
                Membros
            </button>

            @if($isMember)
                <button
                    wire:click="changeTab('invitations')"
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ $tab === 'invitations' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    Convidar
                </button>
            @endif

            @if($isAdmin || $isModerator)
                <button
                    wire:click="changeTab('settings')"
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ $tab === 'settings' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    Configurações
                </button>
            @endif
        </nav>
    </div>

    <!-- Conteúdo da aba selecionada -->
    <div>
        @if($tab === 'posts')
            {{-- <livewire:groups.group-posts :group="$group" :key="'posts-'.$group->id" /> --}}
        @elseif($tab === 'members')
            <livewire:groups.group-members :group="$group" :key="'members-'.$group->id" />
        @elseif($tab === 'invitations' && $isMember)
            <livewire:groups.group-invitations :group="$group" :key="'invitations-'.$group->id" />
        @elseif($tab === 'settings' && ($isAdmin || $isModerator))
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Configurações do Grupo</h2>

                <div class="space-y-4">
                    <flux:button href="{{ route('grupos.edit', $group) }}" color="secondary">
                        <x-flux::icon icon="pencil" class="w-4 h-4 mr-2" />
                        Editar Informações do Grupo
                    </flux:button>

                    @if($isAdmin)
                        <form action="{{ route('grupos.destroy', $group) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este grupo? Esta ação não pode ser desfeita.')">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" color="danger">
                                <x-flux::icon icon="trash" class="w-4 h-4 mr-2" />
                                Excluir Grupo
                            </flux:button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Modal de confirmação para entrar no grupo -->
    <flux:modal wire:model="showJoinConfirmation">
        <flux:modal.header>
            <flux:modal.title>Entrar no Grupo</flux:modal.title>
        </flux:modal.header>

        <flux:modal.body>
            <p>Você tem certeza que deseja entrar no grupo <strong>{{ $group->name }}</strong>?</p>

            @if($group->privacy === 'private')
                <p class="mt-2 text-sm text-gray-500">Este é um grupo privado. Sua solicitação precisará ser aprovada por um administrador.</p>
            @endif
        </flux:modal.body>

        <flux:modal.footer>
            <flux:button wire:click="$wire.set('showJoinConfirmation', false)" color="secondary">
                Cancelar
            </flux:button>

            <flux:button wire:click="join" color="primary">
                Confirmar
            </flux:button>
        </flux:modal.footer>
    </flux:modal>

    <!-- Modal de confirmação para sair do grupo -->
    <flux:modal wire:model="showLeaveConfirmation">
        <flux:modal.header>
            <flux:modal.title>Sair do Grupo</flux:modal.title>
        </flux:modal.header>

        <flux:modal.body>
            <p>Você tem certeza que deseja sair do grupo <strong>{{ $group->name }}</strong>?</p>
            <p class="mt-2 text-sm text-gray-500">Você não terá mais acesso às postagens e conteúdos exclusivos deste grupo.</p>
        </flux:modal.body>

        <flux:modal.footer>
            <flux:button wire:click="$wire.set('showLeaveConfirmation', false)" color="secondary">
                Cancelar
            </flux:button>

            <flux:button wire:click="leave" color="danger">
                Sair do Grupo
            </flux:button>
        </flux:modal.footer>
    </flux:modal>
</div>
