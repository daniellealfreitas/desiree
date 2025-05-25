<div
    id="toast-notification-container"
    wire:id="toast-notification"
    x-data="{ refreshCount: 0 }"
    x-init="
        // Adicionar listener para eventos de toast
        window.addEventListener('toast-added', () => {
            refreshCount++;
            // Comentado para evitar loop de refresh
            // if ($wire) $wire.refreshComponent();
        });
        // Log para debug
        console.log('Toast notification component initialized');
    "
>
    <!-- Botão de teste escondido, acessível apenas via código -->
    <div class="">
        <button
            id="toast-test-button"
            wire:click="testToast"
        >
            {{ $testMessage }}
        </button>
    </div>

    <!-- Área de notificações -->
    <div class="fixed bottom-4 right-4 z-50 w-full max-w-sm space-y-2 lg:mr-4">
        @foreach($notifications as $notification)
            <div
                wire:key="toast-{{ $notification['id'] }}"
                x-data="{ show: true }"
                x-init="
                    setTimeout(() => { show = false }, {{ $notification['timeout'] ?? 5000 }});
                    $el.classList.add('animate__animated', 'animate__fadeInUp');
                    $el.addEventListener('animationend', () => {
                        if (!show) {
                            $el.classList.remove('animate__fadeInUp');
                            $el.classList.add('animate__fadeOutDown');
                        }
                    });
                "
                x-show="show"
                x-transition:leave-end="transform opacity-0 translate-y-4"
                class="w-full max-w-sm overflow-hidden rounded-lg shadow-xl border transform transition-all duration-300 bg-zinc-800 text-white
                    @if($notification['type'] === 'success') border-green-700
                    @elseif($notification['type'] === 'error') border-red-700
                    @elseif($notification['type'] === 'message') border-purple-700
                    @else border-gray-700
                    @endif"
            >
                <div class="flex">
                    <!-- Barra lateral colorida -->
                    <div class="w-2
                        @if($notification['type'] === 'success') bg-green-500
                        @elseif($notification['type'] === 'error') bg-red-500
                        @elseif($notification['type'] === 'message') bg-purple-500
                        @else bg-blue-500
                        @endif">
                    </div>

                    <div class="p-4 flex-1">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                            @if($notification['type'] === 'success')
                                <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($notification['type'] === 'error')
                                <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            @elseif($notification['type'] === 'message')
                                @if(isset($notification['avatar']) && $notification['avatar'])
                                    <img src="{{ $notification['avatar'] }}" class="h-8 w-8 rounded-full object-cover" alt="Avatar">
                                @else
                                    <svg class="h-6 w-6 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                    </svg>
                                @endif
                            @else
                                <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-white">
                                {{ $notification['message'] }}
                            </p>

                            @if($notification['type'] === 'message')
                                <div class="mt-3">
                                    <button
                                        wire:click="goToMessages({{ $notification['sender_id'] ?? 'null' }})"
                                        class="inline-flex items-center rounded-md bg-purple-600 px-2 py-1 text-xs font-medium text-white hover:bg-purple-700 focus:outline-none"
                                    >
                                        Ver Mensagem
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 flex flex-shrink-0">
                            <button
                                wire:click="removeToast('{{ $notification['id'] }}')"
                                class="inline-flex rounded-md text-gray-300 hover:text-white"
                            >
                                <span class="sr-only">Fechar</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Botão para limpar notificações (escondido) -->
    <div class="hidden">
        <button
            id="clear-toasts-button"
            wire:click="clearAllToasts"
        >
            Limpar
        </button>
    </div>
</div>
