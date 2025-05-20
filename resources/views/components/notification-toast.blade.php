@props(['position' => 'top-right'])

@php
    $positionClasses = [
        'top-right' => 'top-4 right-4',
        'top-left' => 'top-4 left-4',
        'bottom-right' => 'bottom-4 right-4',
        'bottom-left' => 'bottom-4 left-4',
        'top-center' => 'top-4 left-1/2 transform -translate-x-1/2',
        'bottom-center' => 'bottom-4 left-1/2 transform -translate-x-1/2',
    ][$position];
@endphp

<div
    x-data="{
        notifications: [],
        add(notification) {
            console.log('Adding notification:', notification);
            notification.id = Date.now()
            this.notifications.push(notification)
            setTimeout(() => this.remove(notification.id), notification.timeout || 5000)
        },
        remove(id) {
            this.notifications = this.notifications.filter(notification => notification.id !== id)
        },
        goToCart() {
            window.location.href = '{{ route('shop.cart') }}'
        },
        continueShopping() {
            // Fechar a notificação e continuar na página atual
            this.notifications = []
        },
        goToMessages() {
            window.location.href = '{{ route('messages') }}'
        }
    }"
    @notify.window="console.log('Notification event received:', $event.detail); add($event.detail)"
    x-init="
        console.log('Notification component initialized');
        // Test notification on init
        setTimeout(() => {
            console.log('Testing notification from component init');
            add({
                message: 'Teste de inicialização do componente',
                type: 'info',
                timeout: 3000
            });
        }, 2000);
    "
    class="fixed z-50 {{ $positionClasses }} w-full max-w-sm space-y-2 pointer-events-none"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-show="true"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg shadow-lg ring-1"
            :class="{
                'bg-zinc-800 dark:bg-zinc-800 ring-gray-200 dark:ring-zinc-700': notification.type === 'info',
                'bg-green-50 dark:bg-green-900 ring-green-200 dark:ring-green-800': notification.type === 'success',
                'bg-red-50 dark:bg-red-900 ring-red-200 dark:ring-red-800': notification.type === 'error',
                'bg-yellow-50 dark:bg-yellow-900 ring-yellow-200 dark:ring-yellow-800': notification.type === 'warning',
                'bg-purple-50 dark:bg-purple-900 ring-purple-200 dark:ring-purple-800': notification.type === 'message'
            }"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Success Icon -->
                        <template x-if="notification.type === 'success'">
                            <svg class="h-6 w-6 text-green-400 dark:text-green-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>

                        <!-- Error Icon -->
                        <template x-if="notification.type === 'error'">
                            <svg class="h-6 w-6 text-red-400 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </template>

                        <!-- Warning Icon -->
                        <template x-if="notification.type === 'warning'">
                            <svg class="h-6 w-6 text-yellow-400 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </template>

                        <!-- Info Icon -->
                        <template x-if="notification.type === 'info'">
                            <svg class="h-6 w-6 text-blue-400 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </template>

                        <!-- Cart Icon (for cart notifications) -->
                        <template x-if="notification.type === 'cart'">
                            <svg class="h-6 w-6 text-indigo-400 dark:text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                        </template>

                        <!-- Message Icon (for message notifications) -->
                        <template x-if="notification.type === 'message'">
                            <div class="relative">
                                <template x-if="notification.avatar">
                                    <img :src="notification.avatar" class="h-8 w-8 rounded-full object-cover" />
                                </template>
                                <template x-if="!notification.avatar">
                                    <svg class="h-6 w-6 text-purple-400 dark:text-purple-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                    </svg>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p
                            x-text="notification.message"
                            class="text-sm font-medium"
                            :class="{
                                'text-gray-900 dark:text-white': notification.type === 'info',
                                'text-green-800 dark:text-green-100': notification.type === 'success',
                                'text-red-800 dark:text-red-100': notification.type === 'error',
                                'text-yellow-800 dark:text-yellow-100': notification.type === 'warning',
                                'text-indigo-800 dark:text-indigo-100': notification.type === 'cart',
                                'text-purple-800 dark:text-purple-100': notification.type === 'message'
                            }"
                        ></p>

                        <!-- Botões de ação para notificações do carrinho -->
                        <template x-if="notification.type === 'cart' || (notification.type === 'success' && notification.message && notification.message.includes('carrinho'))">
                            <div class="mt-3 flex space-x-2">
                                <button
                                    @click="goToCart()"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-2 py-1 text-xs font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Ver Carrinho
                                </button>
                                <button
                                    @click="continueShopping()"
                                    class="inline-flex items-center rounded-md bg-white px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-zinc-700 dark:text-gray-200 dark:hover:bg-zinc-600"
                                >
                                    Continuar Comprando
                                </button>
                            </div>
                        </template>

                        <!-- Botões de ação para notificações de mensagens -->
                        <template x-if="notification.type === 'message'">
                            <div class="mt-3 flex space-x-2">
                                <button
                                    @click="goToMessages()"
                                    class="inline-flex items-center rounded-md bg-purple-600 px-2 py-1 text-xs font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                                >
                                    Ver Mensagem
                                </button>
                            </div>
                        </template>
                    </div>
                    <div class="ml-4 flex flex-shrink-0">
                        <button
                            @click="remove(notification.id)"
                            class="inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
                            :class="{
                                'text-gray-400 hover:text-gray-500 focus:ring-gray-400 focus:ring-offset-gray-50 dark:text-gray-300 dark:hover:text-gray-100 dark:focus:ring-gray-500 dark:focus:ring-offset-gray-900': notification.type === 'info',
                                'text-green-400 hover:text-green-500 focus:ring-green-400 focus:ring-offset-green-50 dark:text-green-300 dark:hover:text-green-100 dark:focus:ring-green-500 dark:focus:ring-offset-green-900': notification.type === 'success',
                                'text-red-400 hover:text-red-500 focus:ring-red-400 focus:ring-offset-red-50 dark:text-red-300 dark:hover:text-red-100 dark:focus:ring-red-500 dark:focus:ring-offset-red-900': notification.type === 'error',
                                'text-yellow-400 hover:text-yellow-500 focus:ring-yellow-400 focus:ring-offset-yellow-50 dark:text-yellow-300 dark:hover:text-yellow-100 dark:focus:ring-yellow-500 dark:focus:ring-offset-yellow-900': notification.type === 'warning',
                                'text-indigo-400 hover:text-indigo-500 focus:ring-indigo-400 focus:ring-offset-indigo-50 dark:text-indigo-300 dark:hover:text-indigo-100 dark:focus:ring-indigo-500 dark:focus:ring-offset-indigo-900': notification.type === 'cart',
                                'text-purple-400 hover:text-purple-500 focus:ring-purple-400 focus:ring-offset-purple-50 dark:text-purple-300 dark:hover:text-purple-100 dark:focus:ring-purple-500 dark:focus:ring-offset-purple-900': notification.type === 'message'
                            }"
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
    </template>
</div>
