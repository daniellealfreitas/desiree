<div class="container mx-auto p-4">
    <div class="flex flex-col md:flex-row gap-4 h-[calc(100vh-12rem)]">
        <!-- Sidebar with conversations -->
        <div class="w-full md:w-1/3 bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-zinc-700 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-title">Conversas</h2>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live="searchTerm"
                        placeholder="Buscar usuários..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-zinc-700"
                    >
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
                                {{ $user->presence_status === 'online' ? 'bg-green-500 animate-pulse' :
                                   ($user->presence_status === 'away' ? 'bg-yellow-500' :
                                    ($user->presence_status === 'dnd' ? 'bg-red-600' : 'bg-gray-500')) }}"></div>
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
                                <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full {{ $conversation['user']->presence_status === 'online' ? 'bg-green-500' : ($conversation['user']->presence_status === 'away' ? 'bg-yellow-500' : 'bg-gray-500') }}"></div>
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
                                        {{ $conversation['latest_message']->created_at->diffForHumans(null, true) }}
                                    </p>
                                </div>
                                <p class="text-sm text-body-lighter truncate w-40">
                                    {{ $conversation['latest_message']->body }}
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
        </div>

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
                                {{ $selectedUser->presence_status === 'online' ? 'bg-green-500 animate-pulse' :
                                   ($selectedUser->presence_status === 'away' ? 'bg-yellow-500' :
                                    ($selectedUser->presence_status === 'dnd' ? 'bg-red-600' : 'bg-gray-500')) }}"></div>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-title">{{ $selectedUser->name }}</p>
                            <p class="text-xs text-body-lighter">
                                @if($selectedUser->presence_status === 'online')
                                    <span class="text-success">Online</span>
                                @elseif($selectedUser->presence_status === 'away')
                                    <span class="text-warning">Ausente</span>
                                @elseif($selectedUser->presence_status === 'dnd')
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
                    <div class="flex-grow overflow-y-auto p-4 space-y-4" id="message-container">
                        @foreach($messages as $message)
                            <div class="flex {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="{{ $message->sender_id == Auth::id() ? 'bg-purple-500 text-white' : 'bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-white' }} rounded-lg p-3 max-w-xs md:max-w-md">
                                    <p>{{ $message->body }}</p>
                                    <p class="text-xs {{ $message->sender_id == Auth::id() ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400' }} text-right mt-1">
                                        {{ $message->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
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
                                <flux:icon name="paper-airplane" class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="flex-grow flex items-center justify-center">
                    <div class="text-center text-body-lighter">
                        <flux:icon name="chat-bubble-left-right" class="w-16 h-16 mx-auto mb-4" />
                        <h3 class="text-xl font-medium mb-2 text-title">Suas mensagens</h3>
                        <p>Selecione uma conversa ou inicie uma nova</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Scroll to bottom of messages when conversation changes or new message is sent
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('message.processed', (message, component) => {
            const container = document.getElementById('message-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    });
</script>
@endpush
