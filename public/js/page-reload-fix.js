/**
 * Page Reload Fix
 *
 * This script helps prevent unnecessary page reloads by patching
 * various browser and framework behaviors.
 */

(function () {
    console.log('[Page Reload Fix] Initializing page reload prevention...');

    // Track page loads
    let pageLoadCount = parseInt(sessionStorage.getItem('pageLoadCount') || '0');
    pageLoadCount++;
    sessionStorage.setItem('pageLoadCount', pageLoadCount.toString());
    console.log(`[Page Reload Fix] Page load count: ${pageLoadCount}`);

    // Verificar se estamos em um loop de recarga
    const lastLoadTime = parseInt(sessionStorage.getItem('lastLoadTime') || '0');
    const currentTime = Date.now();
    sessionStorage.setItem('lastLoadTime', currentTime.toString());

    // Se a página foi carregada nos últimos 2 segundos, pode ser um loop de recarga
    if (lastLoadTime > 0 && currentTime - lastLoadTime < 2000) {
        console.warn('[Page Reload Fix] Possível loop de recarga detectado! Intervalo entre cargas:', currentTime - lastLoadTime, 'ms');

        // Incrementar contador de recargas rápidas
        const rapidReloads = parseInt(sessionStorage.getItem('rapidReloads') || '0') + 1;
        sessionStorage.setItem('rapidReloads', rapidReloads.toString());

        // Se tivermos mais de 3 recargas rápidas consecutivas, tentar interromper o ciclo
        if (rapidReloads > 3) {
            console.error('[Page Reload Fix] Loop de recarga confirmado! Tentando interromper...');

            // Desativar temporariamente todos os wire:poll
            document.addEventListener('DOMContentLoaded', () => {
                const pollElements = document.querySelectorAll('[wire\\:poll]');
                pollElements.forEach(el => {
                    console.log('[Page Reload Fix] Desativando wire:poll em:', el);
                    el.setAttribute('data-original-poll', el.getAttribute('wire:poll'));
                    el.removeAttribute('wire:poll');
                });

                // Mostrar mensagem para o usuário
                const alertDiv = document.createElement('div');
                alertDiv.style.position = 'fixed';
                alertDiv.style.top = '10px';
                alertDiv.style.left = '50%';
                alertDiv.style.transform = 'translateX(-50%)';
                alertDiv.style.zIndex = '9999';
                alertDiv.style.padding = '10px 20px';
                alertDiv.style.backgroundColor = '#f8d7da';
                alertDiv.style.color = '#721c24';
                alertDiv.style.borderRadius = '5px';
                alertDiv.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
                alertDiv.textContent = 'Detectamos recargas automáticas. Alguns recursos foram temporariamente desativados para estabilizar a página.';
                document.body.appendChild(alertDiv);

                // Remover a mensagem após 10 segundos
                setTimeout(() => {
                    alertDiv.remove();
                }, 10000);
            });

            // Resetar o contador após intervenção
            sessionStorage.setItem('rapidReloads', '0');
        }
    } else {
        // Se não estamos em um loop, resetar o contador
        sessionStorage.setItem('rapidReloads', '0');
    }

    // Patch window.location.reload to log when it's called
    const originalReload = window.location.reload;
    window.location.reload = function () {
        console.warn('[Page Reload Fix] window.location.reload() was called. Stack trace:', new Error().stack);

        // Verificar se estamos em um loop de recarga
        const reloadCount = parseInt(sessionStorage.getItem('reloadCount') || '0') + 1;
        sessionStorage.setItem('reloadCount', reloadCount.toString());

        // Se tivermos mais de 3 recargas em 10 segundos, bloquear temporariamente
        const lastReloadCheck = parseInt(sessionStorage.getItem('lastReloadCheck') || '0');
        if (currentTime - lastReloadCheck < 10000) {
            if (reloadCount > 3) {
                console.error('[Page Reload Fix] Muitas chamadas para location.reload(). Bloqueando temporariamente.');
                sessionStorage.setItem('reloadCount', '0');
                return Promise.resolve(); // Não recarregar
            }
        } else {
            // Resetar contador a cada 10 segundos
            sessionStorage.setItem('reloadCount', '1');
            sessionStorage.setItem('lastReloadCheck', currentTime.toString());
        }

        return originalReload.apply(this, arguments);
    };

    // Patch history API to log navigation
    const originalPushState = history.pushState;
    history.pushState = function () {
        console.log('[Page Reload Fix] history.pushState called with:', arguments);
        return originalPushState.apply(this, arguments);
    };

    const originalReplaceState = history.replaceState;
    history.replaceState = function () {
        console.log('[Page Reload Fix] history.replaceState called with:', arguments);
        return originalReplaceState.apply(this, arguments);
    };

    // Detect Livewire initialization
    document.addEventListener('livewire:initialized', () => {
        console.log('[Page Reload Fix] Livewire initialized');

        // Patch Livewire's navigate method to prevent duplicate navigations
        if (window.Livewire && window.Livewire.navigate) {
            const originalNavigate = window.Livewire.navigate;
            let lastNavigationUrl = null;
            let lastNavigationTime = 0;

            window.Livewire.navigate = function (url) {
                const now = Date.now();

                // Prevent duplicate navigations within 1 second
                if (url === lastNavigationUrl && now - lastNavigationTime < 1000) {
                    console.warn('[Page Reload Fix] Prevented duplicate Livewire navigation to:', url);
                    return Promise.resolve();
                }

                lastNavigationUrl = url;
                lastNavigationTime = now;
                console.log('[Page Reload Fix] Livewire navigating to:', url);
                return originalNavigate.apply(this, arguments);
            };
        }

        // Otimizar wire:poll para evitar sobreposição
        const pollComponents = new Set();

        // Interceptar atualizações de polling para evitar sobreposição
        Livewire.hook('message.sent', (message, component) => {
            // Verificar se é uma atualização de polling
            if (message.updateQueue && message.updateQueue.some(update => update.type === 'syncInput' && update.payload.name === '$polling')) {
                const componentId = component.id;

                // Se o componente já está em atualização, cancelar
                if (pollComponents.has(componentId)) {
                    console.log('[Page Reload Fix] Cancelando polling sobreposto para componente:', componentId);
                    return false; // Cancelar a atualização
                }

                // Marcar o componente como em atualização
                pollComponents.add(componentId);

                // Remover o componente da lista após a conclusão
                Livewire.hook('message.processed', (message, component) => {
                    if (component.id === componentId) {
                        pollComponents.delete(componentId);
                    }
                });
            }
        });
    });

    // Detect Alpine initialization
    if (window.Alpine) {
        console.log('[Page Reload Fix] Alpine already loaded');
        patchAlpine();
    } else {
        // Wait for Alpine to load
        Object.defineProperty(window, 'Alpine', {
            configurable: true,
            enumerable: true,
            get: function () {
                return this._Alpine;
            },
            set: function (value) {
                this._Alpine = value;
                console.log('[Page Reload Fix] Alpine loaded');
                patchAlpine();
            }
        });
    }

    function patchAlpine() {
        if (!window.Alpine || window._alpinePatched) return;

        // Mark as patched to prevent multiple patches
        window._alpinePatched = true;

        // Patch Alpine's start method
        const originalStart = window.Alpine.start;
        window.Alpine.start = function () {
            console.log('[Page Reload Fix] Alpine.start() called');
            return originalStart.apply(this, arguments);
        };
    }

    // Add a global error handler to catch and log errors
    window.addEventListener('error', function (event) {
        console.error('[Page Reload Fix] Uncaught error:', event.error);
    });

    // Monitor for rapid navigation events that might indicate a reload loop
    let navigationCount = 0;
    let navigationTimer = null;

    function detectNavigationLoop() {
        navigationCount++;

        if (navigationTimer) {
            clearTimeout(navigationTimer);
        }

        navigationTimer = setTimeout(() => {
            if (navigationCount > 3) {
                console.warn(`[Page Reload Fix] Detected ${navigationCount} navigation events in 3 seconds. Possible reload loop.`);
            }
            navigationCount = 0;
        }, 3000);
    }

    // Listen for navigation events
    window.addEventListener('popstate', detectNavigationLoop);
    window.addEventListener('livewire:navigating', detectNavigationLoop);

    console.log('[Page Reload Fix] Initialization complete');
})();
