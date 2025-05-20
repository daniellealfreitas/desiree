/**
 * Toast Notification Handler - Script para gerenciar notificações toast em tempo real
 *
 * Este script adiciona suporte para notificações toast em tempo real em todas as páginas
 */
document.addEventListener('livewire:init', function () {
    console.log('Toast Notification Handler: Inicializado - Versão 2.0');

    // Verificar se o componente toast-notification existe
    setTimeout(() => {
        const toastContainer = document.getElementById('toast-notification-container');
        if (toastContainer) {
            console.log('Toast notification container encontrado:', toastContainer);
        } else {
            console.warn('Toast notification container NÃO encontrado!');
        }

        if (Livewire.getByName('toast-notification')) {
            console.log('Componente toast-notification encontrado via Livewire.getByName');
        } else {
            console.warn('Componente toast-notification NÃO encontrado via Livewire.getByName');
        }
    }, 1000);
    console.log('Toast Notification Handler: Inicializado');

    // Adicionar método para mostrar notificação diretamente
    window.showToast = function (message, type = 'message', timeout = 5000, avatar = null, senderId = null) {
        console.log('Mostrando toast diretamente:', message);

        // Tentar diferentes abordagens para garantir que a notificação seja exibida

        // 1. Verificar se o componente toast-notification existe via Livewire.getByName
        if (Livewire.getByName('toast-notification')) {
            console.log('Componente toast-notification encontrado via Livewire.getByName, enviando notificação');

            try {
                // Chamar o método showToast do componente
                Livewire.getByName('toast-notification')[0].call('showToast',
                    message,
                    type,
                    timeout,
                    avatar,
                    senderId
                );

                // Disparar evento para atualizar o componente
                document.dispatchEvent(new CustomEvent('toast-added'));

                return true;
            } catch (error) {
                console.error('Erro ao chamar showToast via Livewire.getByName:', error);
            }
        }

        // 2. Verificar se o componente toast-notification existe via wire:id
        const toastComponent = document.querySelector('[wire\\:id="toast-notification"]');
        if (toastComponent) {
            console.log('Componente toast-notification encontrado via wire:id, enviando notificação');

            try {
                // Obter o componente Livewire
                const wireComponent = Livewire.find(toastComponent.getAttribute('wire:id'));

                if (wireComponent) {
                    // Chamar o método showToast do componente
                    wireComponent.call('showToast',
                        message,
                        type,
                        timeout,
                        avatar,
                        senderId
                    );

                    // Disparar evento para atualizar o componente
                    document.dispatchEvent(new CustomEvent('toast-added'));

                    return true;
                }
            } catch (error) {
                console.error('Erro ao chamar showToast via wire:id:', error);
            }
        }

        // 3. Tentar criar uma notificação manualmente
        try {
            console.log('Tentando criar notificação manualmente');

            // Criar elemento de notificação
            const notification = document.createElement('div');
            notification.className = 'fixed bottom-4 right-4 z-50 w-full max-w-sm overflow-hidden rounded-lg shadow-xl border transform transition-all duration-300 bg-purple-50 border-purple-200 dark:border-purple-700 dark:bg-purple-900/50';
            notification.style.animation = 'fadeInUp 0.3s ease-out forwards';

            // Adicionar conteúdo
            notification.innerHTML = `
                <div class="flex">
                    <div class="w-2 bg-purple-500"></div>
                    <div class="p-4 flex-1">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                ${avatar ? `<img src="${avatar}" class="h-8 w-8 rounded-full object-cover" alt="Avatar">` :
                    `<svg class="h-6 w-6 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                </svg>`}
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${message}
                                </p>
                                ${type === 'message' && senderId ? `
                                <div class="mt-3">
                                    <button
                                        class="inline-flex items-center rounded-md bg-purple-600 px-2 py-1 text-xs font-medium text-white hover:bg-purple-700 focus:outline-none"
                                        onclick="window.location.href='/caixa_de_mensagens'"
                                    >
                                        Ver Mensagem
                                    </button>
                                </div>` : ''}
                            </div>
                            <div class="ml-4 flex flex-shrink-0">
                                <button
                                    class="inline-flex rounded-md text-gray-400 hover:text-gray-500"
                                    onclick="this.parentElement.parentElement.parentElement.parentElement.remove()"
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
            `;

            // Adicionar ao DOM
            document.body.appendChild(notification);

            // Remover após o timeout
            setTimeout(() => {
                notification.style.animation = 'fadeOutDown 0.3s ease-in forwards';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, timeout);

            // Adicionar estilos de animação se não existirem
            if (!document.getElementById('toast-animation-styles')) {
                const style = document.createElement('style');
                style.id = 'toast-animation-styles';
                style.textContent = `
                    @keyframes fadeInUp {
                        from { opacity: 0; transform: translateY(20px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    @keyframes fadeOutDown {
                        from { opacity: 1; transform: translateY(0); }
                        to { opacity: 0; transform: translateY(20px); }
                    }
                `;
                document.head.appendChild(style);
            }

            return true;
        } catch (error) {
            console.error('Erro ao criar notificação manualmente:', error);
        }

        console.warn('Nenhuma abordagem funcionou para mostrar a notificação');
        return false;
    };

    // Adicionar método global para testar notificações (apenas para desenvolvimento)
    window.testToast = function (type = 'message') {
        if (window.location.hostname !== 'localhost' && !window.location.hostname.includes('127.0.0.1')) {
            console.log('Função de teste desativada em ambiente de produção');
            return false;
        }

        console.log('Testando notificação toast do tipo:', type);

        // Tentar diferentes abordagens para garantir que a notificação seja exibida

        // 1. Verificar se o componente toast-notification existe
        if (Livewire.getByName('toast-notification')) {
            console.log('Componente toast-notification encontrado, enviando notificação');

            // Chamar o método testToast do componente
            Livewire.getByName('toast-notification')[0].call('testToast');
            return true;
        }

        // 2. Verificar se o componente messages existe
        if (Livewire.getByName('messages')) {
            console.log('Componente messages encontrado, enviando notificação');

            // Chamar o método testMessageNotification do componente
            Livewire.getByName('messages')[0].call('testMessageNotification');
            return true;
        }

        // 3. Tentar usar o método showToast diretamente
        if (window.showToast) {
            console.log('Usando método showToast diretamente');
            window.showToast('Esta é uma mensagem de teste em tempo real', type, 5000);
            return true;
        }

        // 4. Tentar usar o método directToast do componente messages
        if (Livewire.getByName('messages')) {
            console.log('Usando método directToast do componente messages');
            Livewire.getByName('messages')[0].call('directToast',
                'Esta é uma mensagem de teste em tempo real',
                type,
                5000
            );
            return true;
        }

        console.warn('Nenhum componente de notificação encontrado');
        return false;
    };

    // Adicionar listener para eventos de browser
    Livewire.on('browser-event', (data) => {
        console.log('Evento browser-event recebido:', data);

        if (data.name === 'showToast' && data.data) {
            console.log('Evento showToast recebido:', data.data);

            // Verificar se o componente toast-notification existe
            if (Livewire.getByName('toast-notification')) {
                console.log('Componente toast-notification encontrado, enviando notificação');

                // Chamar o método showToast do componente
                Livewire.getByName('toast-notification')[0].call('showToast',
                    data.data.message,
                    data.data.type,
                    data.data.timeout,
                    data.data.avatar,
                    data.data.senderId
                );

                // Disparar evento para atualizar o componente
                document.dispatchEvent(new CustomEvent('toast-added'));

                return true;
            }
        }
    });

    // Adicionar listener para eventos toast
    Livewire.on('toast', (data) => {
        console.log('Evento toast recebido diretamente:', data);

        // Verificar se o componente toast-notification existe
        if (Livewire.getByName('toast-notification')) {
            console.log('Componente toast-notification encontrado, enviando notificação diretamente');

            // Chamar o método showToast do componente
            Livewire.getByName('toast-notification')[0].call('showToast',
                data.message,
                data.type,
                data.timeout,
                data.avatar,
                data.senderId
            );

            // Disparar evento para atualizar o componente
            document.dispatchEvent(new CustomEvent('toast-added'));

            return true;
        }
    });

    // Adicionar listener para eventos de notificação
    Livewire.on('notify', (data) => {
        console.log('Evento notify recebido:', data);

        // Verificar se o componente toast-notification existe
        if (Livewire.getByName('toast-notification')) {
            console.log('Componente toast-notification encontrado, enviando notificação');

            // Chamar o método showToast do componente
            Livewire.getByName('toast-notification')[0].call('showToast',
                data.message || 'Nova notificação',
                data.type || 'info',
                data.timeout || 5000,
                data.avatar || null,
                data.senderId || null
            );
        }
    });
});
