/**
 * Livewire Fix
 *
 * Este script corrige problemas comuns com o Livewire 3, incluindo o erro
 * "Could not find Livewire component in DOM tree"
 */

document.addEventListener('livewire:initialized', function () {
    console.log('[Livewire Fix] Inicializando correções para o Livewire...');

    // Verificar se o Livewire está disponível
    if (!window.Livewire) {
        console.warn('[Livewire Fix] Livewire não encontrado no escopo global');
        return;
    }

    // Patch para o método closestComponent
    if (window.Livewire.closestComponent) {
        const originalClosestComponent = window.Livewire.closestComponent;

        window.Livewire.closestComponent = function (el) {
            try {
                return originalClosestComponent(el);
            } catch (error) {
                console.warn('[Livewire Fix] Erro ao encontrar componente mais próximo:', error.message);

                // Tentar encontrar o componente de outra forma
                if (el && el.closest) {
                    const closestEl = el.closest('[wire\\:id]');
                    if (closestEl) {
                        const wireId = closestEl.getAttribute('wire:id');
                        if (wireId && window.Livewire.find) {
                            try {
                                return window.Livewire.find(wireId);
                            } catch (e) {
                                console.error('[Livewire Fix] Não foi possível encontrar componente com ID:', wireId);
                            }
                        }
                    }
                }

                // Retornar um objeto vazio para evitar erros
                return {
                    get: function () { return null; },
                    call: function () { return null; },
                    $wire: {}
                };
            }
        };

        console.log('[Livewire Fix] Método closestComponent corrigido');
    }

    // Patch para o método get
    if (window.Livewire.get) {
        const originalGet = window.Livewire.get;

        window.Livewire.get = function (el, property) {
            try {
                return originalGet(el, property);
            } catch (error) {
                console.warn('[Livewire Fix] Erro ao obter propriedade do componente:', error.message);
                return null;
            }
        };

        console.log('[Livewire Fix] Método get corrigido');
    }

    // Não verificar componentes na página para evitar recarregamentos desnecessários

    console.log('[Livewire Fix] Correções aplicadas com sucesso');
});
