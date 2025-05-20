<div class="container mx-auto p-4" wire:poll.2s>
    <div class="flex flex-col md:flex-row gap-4 h-[calc(100vh-12rem)]">
        <!-- Sidebar with conversations -->
        <section id="conversations" class="w-full md:w-1/3 bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-zinc-700 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-title">Conversas</h2>
                <div class="flex items-center gap-2">
                    <!-- Status selector -->
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            type="button"
                            class="flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white"
                        >
                            <span class="w-3 h-3 rounded-full mr-1
                                {{ Auth::user()->status === 'online' ? 'bg-green-500' :
                                   (Auth::user()->status === 'away' ? 'bg-yellow-500' :
                                    (Auth::user()->status === 'dnd' ? 'bg-red-600' : 'bg-gray-500')) }}"></span>
                            <span>{{ ucfirst(Auth::user()->status ?: 'offline') }}</span>
                            <x-flux::icon name="chevron-down" class="w-4 h-4 ml-1" />
                        </button>

                        <!-- Dropdown -->
                        <div
                            x-show="open"
                            @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-800 rounded-md shadow-lg z-10"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            style="display: none;"
                        >
                            <div class="py-1">
                                <button
                                    wire:click="updateUserStatus('online')"
                                    type="button"
                                    @click="open = false"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700"
                                >
                                    <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                    Online
                                </button>
                                <button
                                    wire:click="updateUserStatus('away')"
                                    type="button"
                                    @click="open = false"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700"
                                >
                                    <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                                    Ausente
                                </button>
                                <button
                                    wire:click="updateUserStatus('dnd')"
                                    type="button"
                                    @click="open = false"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700"
                                >
                                    <span class="w-3 h-3 rounded-full bg-red-600 mr-2"></span>
                                    Não Perturbe
                                </button>
                                <button
                                    wire:click="updateUserStatus('offline')"
                                    type="button"
                                    @click="open = false"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700"
                                >
                                    <span class="w-3 h-3 rounded-full bg-gray-500 mr-2"></span>
                                    Offline
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search input -->
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live="searchTerm"
                            placeholder="Buscar usuários..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-zinc-700"
                        >
                    </div>
                </div>
            </div>

            <div class="overflow-y-auto flex-grow">
                @if($searchTerm)
                    <!-- Search results -->
                    <div class="p-2">
                        <h3 class="text-sm font-medium text-body-lighter mb-2">Resultados da busca</h3>
                        @forelse($users as $user)
                            <div
                                wire:click="startNewConversation({{ $user->id }})"
                                class="flex items-center p-3 hover:bg-gray-100 dark:hover:bg-zinc-700 rounded-lg cursor-pointer"
                            >
                                <div class="relative">
                                    <img
                                        src="{{ $user->userPhotos->first() ? asset($user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                    <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full
                                {{ $user->status === 'online' ? 'bg-green-500 animate-pulse' :
                                   ($user->status === 'away' ? 'bg-yellow-500' :
                                    ($user->status === 'dnd' ? 'bg-red-600' : 'bg-gray-500')) }}"></div>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-title">{{ $user->name }}</p>
                                    <p class="text-xs text-body-lighter">@{{ $user->username }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-body-lighter py-4">Nenhum usuário encontrado</p>
                        @endforelse
                    </div>
                @else
                    <!-- Conversation list -->
                    @forelse($conversations as $conversation)
                        <div
                            wire:click="selectConversation({{ $conversation['user']->id }})"
                            class="flex items-center p-3 hover:bg-gray-100 dark:hover:bg-zinc-700 cursor-pointer {{ $selectedConversation == $conversation['user']->id ? 'bg-purple-50 dark:bg-zinc-700' : '' }}"
                        >
                            <div class="relative">
                                <img
                                    src="{{ $conversation['user']->userPhotos->first() ? asset($conversation['user']->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                    class="w-10 h-10 rounded-full object-cover"
                                >
                                <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full {{ $conversation['user']->status === 'online' ? 'bg-green-500' : ($conversation['user']->status === 'away' ? 'bg-yellow-500' : 'bg-gray-500') }}"></div>
                                @if($conversation['unread_count'] > 0)
                                    <div class="absolute -top-1 -right-1 bg-purple-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ $conversation['unread_count'] }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3 flex-grow">
                                <div class="flex justify-between items-center">
                                    <p class="font-medium text-title">{{ $conversation['user']->name }}</p>
                                    <p class="text-xs text-body-lighter">
                                        @if($conversation['latest_message'])
                                            {{ $conversation['latest_message']->created_at->diffForHumans(null, true) }}
                                        @else
                                            Sem mensagens
                                        @endif
                                    </p>
                                </div>
                                <p class="text-sm text-body-lighter truncate w-40">
                                    @if($conversation['latest_message'])
                                        {{ $conversation['latest_message']->body }}
                                    @else
                                        Inicie uma conversa
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-body-lighter py-8">
                            <p>Nenhuma conversa encontrada</p>
                            <p class="text-sm mt-2">Use a busca para iniciar uma conversa</p>
                        </div>
                    @endforelse
                @endif
            </div>
        </section>

        <!-- Message area -->
        <div class="w-full md:w-2/3 bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden flex flex-col">
            @if($selectedConversation)
                @php
                    $selectedUser = $users->firstWhere('id', $selectedConversation) ??
                        collect($conversations)->firstWhere('user.id', $selectedConversation)['user'] ?? null;
                @endphp

                @if($selectedUser)
                    <!-- Conversation header -->
                    <div class="p-4 border-b border-gray-200 dark:border-zinc-700 flex items-center">
                        <div class="relative">
                            <img
                                src="{{ $selectedUser->userPhotos->first() ? asset($selectedUser->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                class="w-10 h-10 rounded-full object-cover"
                            >
                            <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full
                                {{ $selectedUser->status === 'online' ? 'bg-green-500 animate-pulse' :
                                   ($selectedUser->status === 'away' ? 'bg-yellow-500' :
                                    ($selectedUser->status === 'dnd' ? 'bg-red-600' : 'bg-gray-500')) }}"></div>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-title">{{ $selectedUser->name }}</p>
                            <p class="text-xs text-body-lighter">
                                @if($selectedUser->status === 'online')
                                    <span class="text-success">Online</span>
                                @elseif($selectedUser->status === 'away')
                                    <span class="text-warning">Ausente</span>
                                @elseif($selectedUser->status === 'dnd')
                                    <span class="text-danger">Não Perturbe</span>
                                @else
                                    <span class="text-body-lighter">Offline</span>
                                    @if($selectedUser->last_seen)
                                        · Visto por último {{ $selectedUser->last_seen->diffForHumans() }}
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="flex-grow overflow-y-auto p-4 space-y-4 relative h-[400px]" id="message-container" style="height: 400px; overflow-y: auto;">
                        <!-- Botão de scroll para o final -->
                        <button
                            id="scroll-to-bottom-btn"
                            class="fixed bottom-20 right-8 bg-purple-600 text-white rounded-full p-3 shadow-lg hover:bg-purple-700 focus:outline-none z-50"
                            type="button"
                            onclick="document.getElementById('message-container').scrollTop = document.getElementById('message-container').scrollHeight;"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </button>

                        <div id="messages-wrapper">
                            @foreach($messages as $message)
                                <div class="flex {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }} mb-4">
                                    <div class="{{ $message->sender_id == Auth::id() ? 'bg-purple-500 text-white' : 'bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-white' }} rounded-lg p-3 max-w-xs md:max-w-md">
                                        <p>{{ $message->body }}</p>
                                        <p class="text-xs {{ $message->sender_id == Auth::id() ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400' }} text-right mt-1">
                                            {{ $message->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Elemento âncora para scroll -->
                            <div id="scroll-anchor" style="height: 1px; margin-top: 20px;"></div>
                        </div>
                    </div>

                    <!-- Message input -->
                    <div class="p-4 border-t border-gray-200 dark:border-zinc-700">
                        <form wire:submit.prevent="sendMessage" class="flex items-center">
                            <input
                                type="text"
                                wire:model="messageBody"
                                placeholder="Digite sua mensagem..."
                                class="flex-grow px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-zinc-700"
                            >
                            <button
                                type="submit"
                                class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-r-lg"
                            >
                                <x-flux::icon name="paper-airplane" class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="flex-grow flex items-center justify-center">
                    <div class="text-center text-body-lighter">
                        <x-flux::icon name="chat-bubble-left-right" class="w-16 h-16 mx-auto mb-4" />
                        <h3 class="text-xl font-medium mb-2 text-title">Suas mensagens</h3>
                        <p>Selecione uma conversa ou inicie uma nova</p>

                        <!-- Mensagem de sessão (se houver) -->
                        @if(session('message'))
                            <div class="mt-4 p-2 bg-green-100 text-green-800 rounded-md">
                                {{ session('message') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Função para scroll direto
    function forceScrollToBottom() {
        try {
            var container = document.getElementById('message-container');
            if (container) {
                // Forçar scroll para o final
                container.scrollTop = 999999;

                // Alternativa usando scrollIntoView
                var anchor = document.getElementById('scroll-anchor');
                if (anchor) {
                    anchor.scrollIntoView({ behavior: 'auto' });
                }
            }
        } catch (e) {
            console.error('Erro ao fazer scroll:', e);
        }
    }

    // Executar scroll em vários momentos
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll inicial
        forceScrollToBottom();

        // Scroll após um pequeno delay para garantir que o DOM está completamente carregado
        setTimeout(forceScrollToBottom, 100);
        setTimeout(forceScrollToBottom, 500);
        setTimeout(forceScrollToBottom, 1000);

        // Scroll periódico (comentado para evitar problemas de recursão)
        // setInterval(forceScrollToBottom, 2000);
    });

    // Scroll após cada atualização do Livewire (atualizado para Livewire 3)
    document.addEventListener('livewire:init', function() {
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                forceScrollToBottom();
            });
        });
    });

    // Scroll após enviar uma mensagem
    document.addEventListener('submit', function(e) {
        if (e.target.closest('form[wire\\:submit\\.prevent="sendMessage"]')) {
            setTimeout(forceScrollToBottom, 100);
        }
    });

    // Adicionar evento de clique ao botão de scroll
    window.addEventListener('load', function() {
        var scrollButton = document.getElementById('scroll-to-bottom-btn');
        if (scrollButton) {
            scrollButton.addEventListener('click', forceScrollToBottom);
        }
    });

    // Adicionar listener para o evento scrollToBottom
    window.addEventListener('scrollToBottom', forceScrollToBottom);

    // Adicionar listener para eventos do browser via Livewire 3
    document.addEventListener('livewire:init', function() {
        Livewire.on('browser-event', function(data) {
            if (data.name === 'scrollToBottom') {
                forceScrollToBottom();
            }
        });
    });

    // Executar scroll imediatamente
    forceScrollToBottom();
</script>
@endpush

<!-- Script inline para garantir o scroll -->
<script>
    // Executar scroll quando a página terminar de carregar
    window.onload = function() {
        // Forçar scroll para o final
        var container = document.getElementById('message-container');
        if (container) {
            container.scrollTop = container.scrollHeight;

            // Alternativa usando scrollIntoView
            var anchor = document.getElementById('scroll-anchor');
            if (anchor) {
                anchor.scrollIntoView({ behavior: 'auto' });
            }
        }
    };
</script>
