/**
 * Toast Notification Fix
 *
 * Este script garante que o componente de notificação toast seja inicializado corretamente
 * e esteja disponível para receber notificações.
 */

// Controle para evitar duplicação de notificações
const processedNotifications = new Set();

document.addEventListener('DOMContentLoaded', function () {
    console.log('[Toast Fix] Verificando componente de notificação toast...');

    // Verificar se o componente toast-notification existe
    const toastComponent = document.querySelector('[wire\\:id="toast-notification"]');

    if (!toastComponent) {
        console.warn('[Toast Fix] Componente toast-notification não encontrado no DOM');

        // Verificar se o componente está registrado no Livewire
        if (window.Livewire && typeof window.Livewire.find === 'function') {
            console.log('[Toast Fix] Verificando se o componente está registrado no Livewire...');

            // Aguardar a inicialização do Livewire
            document.addEventListener('livewire:init', function () {
                console.log('[Toast Fix] Livewire inicializado, verificando componentes...');

                // Tentar encontrar o componente pelo nome
                if (typeof Livewire.getByName === 'function' && Livewire.getByName('toast-notification').length > 0) {
                    console.log('[Toast Fix] Componente toast-notification encontrado via Livewire.getByName');
                } else {
                    console.warn('[Toast Fix] Componente toast-notification não encontrado via Livewire.getByName');

                    // Tentar criar o componente manualmente
                    console.log('[Toast Fix] Tentando criar o componente manualmente...');

                    // Criar um elemento para o componente
                    const toastContainer = document.createElement('div');
                    toastContainer.setAttribute('wire:id', 'toast-notification');
                    toastContainer.setAttribute('wire:poll.500ms', '');
                    toastContainer.style.display = 'none';

                    // Adicionar ao body
                    document.body.appendChild(toastContainer);

                    console.log('[Toast Fix] Elemento criado e adicionado ao DOM');
                }
            });
        }
    } else {
        console.log('[Toast Fix] Componente toast-notification encontrado no DOM');
    }

    // Garantir que o evento de notificação seja processado sem duplicações
    window.addEventListener('notify', function (event) {
        console.log('[Toast Fix] Evento notify recebido:', event.detail);

        // Criar um identificador único para a notificação
        const notificationId = `${event.detail.message}-${Date.now()}`;

        // Verificar se esta notificação já foi processada recentemente (nos últimos 2 segundos)
        if (processedNotifications.has(notificationId)) {
            console.log('[Toast Fix] Notificação duplicada ignorada:', notificationId);
            return;
        }

        // Adicionar à lista de notificações processadas
        processedNotifications.add(notificationId);

        // Remover da lista após 2 segundos para evitar acúmulo de memória
        setTimeout(() => {
            processedNotifications.delete(notificationId);
        }, 2000);

        // Verificar se o componente toast-notification existe
        const toastComponent = document.querySelector('[wire\\:id="toast-notification"]');

        if (toastComponent && window.Livewire) {
            console.log('[Toast Fix] Encaminhando notificação para o componente toast-notification');

            try {
                // Tentar diferentes abordagens para enviar a notificação

                // 1. Via Livewire.find
                if (typeof window.Livewire.find === 'function') {
                    const wireId = toastComponent.getAttribute('wire:id');
                    const component = window.Livewire.find(wireId);

                    if (component && typeof component.call === 'function') {
                        component.call('showToast',
                            event.detail.message,
                            event.detail.type,
                            event.detail.timeout,
                            event.detail.avatar,
                            event.detail.senderId
                        );

                        console.log('[Toast Fix] Notificação enviada via Livewire.find');
                        return;
                    }
                }

                // 2. Via Livewire.dispatch
                if (typeof window.Livewire.dispatch === 'function') {
                    window.Livewire.dispatch('toast', {
                        message: event.detail.message,
                        type: event.detail.type,
                        timeout: event.detail.timeout,
                        avatar: event.detail.avatar,
                        senderId: event.detail.senderId
                    });

                    console.log('[Toast Fix] Notificação enviada via Livewire.dispatch');
                    return;
                }

                console.warn('[Toast Fix] Não foi possível enviar a notificação para o componente');
            } catch (error) {
                console.error('[Toast Fix] Erro ao enviar notificação:', error);
            }
        }
    });
});
