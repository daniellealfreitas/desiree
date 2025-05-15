/**
 * Alpine.js Early Initialization
 *
 * Este script é carregado o mais cedo possível para garantir que nosso patch
 * para o Alpine.js seja aplicado antes de qualquer outra inicialização.
 */

// Importar o Alpine.js
import Alpine from 'alpinejs';

// Verificar se o Alpine.js já está definido globalmente
if (!window.Alpine) {
    console.info('[Alpine] Inicializando Alpine.js pela primeira vez');

    // Se não estiver definido, definir globalmente
    window.Alpine = Alpine;

    // Patch para o método start() para evitar múltiplas inicializações
    const originalStart = Alpine.start;
    Alpine.start = function () {
        if (window._alpineInitialized) {
            console.info('[Alpine] Alpine.start() foi chamado novamente, mas Alpine já foi inicializado. Ignorando.');
            return;
        }

        console.info('[Alpine] Inicializando Alpine.js');
        window._alpineInitialized = true;
        return originalStart.call(this);
    };

    // Prevenir que o Alpine seja redefinido
    Object.defineProperty(window, 'Alpine', {
        configurable: false,
        writable: false,
        value: Alpine
    });

    // Adicionar um mecanismo para evitar recargas desnecessárias durante navegação
    document.addEventListener('livewire:initialized', () => {
        window.addEventListener('livewire:navigating', () => {
            console.info('[Alpine] Navegação Livewire iniciada, preservando estado do Alpine');
            // Salvar o estado do Alpine antes da navegação
            window._alpineState = true;
        });

        window.addEventListener('livewire:navigated', () => {
            console.info('[Alpine] Navegação Livewire concluída');
            // Restaurar o estado do Alpine após a navegação
            if (window._alpineState) {
                // Já está inicializado, não precisa inicializar novamente
                window._alpineState = false;
            }
        });
    });
} else {
    console.warn('[Alpine] Alpine.js já está definido globalmente. Usando a instância existente.');
}

// Não exportar nada, este script é apenas para aplicar o patch
