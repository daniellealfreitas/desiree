/**
 * Alpine.js Configuration
 *
 * Este arquivo configura o Alpine.js para toda a aplicação.
 * Ele garante que apenas uma instância do Alpine.js seja carregada e inicializada uma única vez.
 */

import Alpine from 'alpinejs';

// Verificar se o Alpine.js já está definido globalmente
if (!window.Alpine) {
    // Se não estiver definido, definir globalmente
    window.Alpine = Alpine;

    // Patch para o método start() para evitar múltiplas inicializações
    const originalStart = Alpine.start;
    Alpine.start = function () {
        if (window._alpineInitialized) {
            console.info('Alpine.start() foi chamado novamente, mas Alpine já foi inicializado. Ignorando.');
            return;
        }

        window._alpineInitialized = true;
        return originalStart.call(this);
    };

    // Adicionar plugins e extensões aqui, se necessário
} else {
    console.info('Alpine.js já está definido globalmente. Usando a instância existente.');

    // Garantir que o patch seja aplicado mesmo se o Alpine já estiver definido
    if (!window._alpineStartPatched) {
        const originalStart = window.Alpine.start;
        window.Alpine.start = function () {
            if (window._alpineInitialized) {
                console.info('Alpine.start() foi chamado novamente, mas Alpine já foi inicializado. Ignorando.');
                return;
            }

            window._alpineInitialized = true;
            return originalStart.call(this);
        };
        window._alpineStartPatched = true;
    }
}

// Exportar a instância do Alpine para uso em outros arquivos
export default window.Alpine;
