<x-layouts.app :title="__('Convites de Grupos')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Convites de Grupos</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gerencie seus convites para grupos</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            @if($pendingInvitations->isEmpty())
                <div class="py-12 flex flex-col items-center justify-center">
                    <x-flux::icon icon="envelope" class="w-16 h-16 text-gray-400 dark:text-gray-500 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum convite pendente</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-center max-w-md">
                        Você não possui convites pendentes para grupos no momento.
                    </p>
                </div>
            @else
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Convites Pendentes</h2>

                <div class="space-y-4">
                    @foreach($pendingInvitations as $invitation)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <img
                                        src="{{ $invitation->group->image ? asset('storage/' . $invitation->group->image) : asset('images/default-group.jpg') }}"
                                        alt="{{ $invitation->group->name }}"
                                        class="w-12 h-12 rounded-lg object-cover"
                                    >
                                </div>

                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $invitation->group->name }}
                                    </h3>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Convidado por {{ $invitation->inviter->name }} {{ $invitation->created_at->diffForHumans() }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <span class="inline-flex items-center">
                                            <x-flux::icon icon="users" class="w-4 h-4 mr-1" />
                                            {{ $invitation->group->members_count }} {{ $invitation->group->members_count == 1 ? 'membro' : 'membros' }}
                                        </span>

                                        <span class="mx-2">•</span>

                                        <span class="inline-flex items-center">
                                            @if($invitation->group->privacy === 'public')
                                                <x-flux::icon icon="globe" class="w-4 h-4 mr-1" />
                                                Público
                                            @elseif($invitation->group->privacy === 'private')
                                                <x-flux::icon icon="lock-closed" class="w-4 h-4 mr-1" />
                                                Privado
                                            @else
                                                <x-flux::icon icon="eye-off" class="w-4 h-4 mr-1" />
                                                Secreto
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <form action="{{ route('grupos.invitations.decline', $invitation) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <flux:button type="submit" color="secondary">
                                        Recusar
                                    </flux:button>
                                </form>

                                <form action="{{ route('grupos.invitations.accept', $invitation) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <flux:button type="submit" color="primary">
                                        Aceitar
                                    </flux:button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
