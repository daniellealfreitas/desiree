/**
 * Tooltip Handler
 *
 * Este script fornece funcionalidades de tooltip para a aplicação.
 * Ele garante que a função showTooltip esteja disponível globalmente para o Alpine.js.
 */

// Definir showTooltip globalmente para evitar erros
window.showTooltip = false;

document.addEventListener('alpine:init', () => {
    // Registrar um componente Alpine global para tooltips
    Alpine.data('tooltip', () => ({
        showTooltip: false,

        init() {
            // Inicialização do tooltip
        },

        toggle() {
            this.showTooltip = !this.showTooltip;
        },

        show() {
            this.showTooltip = true;
        },

        hide() {
            this.showTooltip = false;
        }
    }));
});

// Garantir que o Alpine.js esteja disponível
document.addEventListener('DOMContentLoaded', () => {
    // Definir showTooltip globalmente para evitar erros
    if (typeof window.showTooltip === 'undefined') {
        window.showTooltip = false;
    }

    // Verificar se há elementos que usam showTooltip sem definir a variável
    const fixTooltips = () => {
        // Encontrar todos os elementos que têm @click="showTooltip=!showTooltip" ou similar
        // mas não têm x-data que define showTooltip
        document.querySelectorAll('[x-data]').forEach(el => {
            const xDataAttr = el.getAttribute('x-data');
            if (!xDataAttr) return;

            const hasClickTooltip = el.hasAttribute('@click') &&
                el.getAttribute('@click').includes('showTooltip') &&
                !xDataAttr.includes('showTooltip');

            // Se encontrar um elemento problemático, corrigir
            if (hasClickTooltip) {
                console.warn('Elemento com @click="showTooltip" sem definir showTooltip no x-data:', el);

                // Adicionar showTooltip ao x-data
                let newXData = xDataAttr.trim();
                if (newXData === '{}') {
                    newXData = '{ showTooltip: false }';
                } else if (newXData.endsWith('}')) {
                    newXData = newXData.slice(0, -1) + ', showTooltip: false }';
                }

                el.setAttribute('x-data', newXData);
            }
        });
    };

    // Executar a correção após o carregamento do DOM
    setTimeout(fixTooltips, 500);
});
