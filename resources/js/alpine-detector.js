/**
 * Alpine.js Instance Detector
 * 
 * Este script detecta se múltiplas instâncias do Alpine.js estão sendo carregadas
 * e registra um aviso no console.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Verificar se o Alpine.js já foi inicializado
    if (window.Alpine && window.Alpine.__initialized) {
        console.warn('Múltiplas instâncias do Alpine.js detectadas. Isso pode causar problemas.');
        console.info('Dica: Certifique-se de que o Alpine.js está sendo importado apenas uma vez em toda a aplicação.');
    }
    
    // Marcar o Alpine.js como inicializado
    if (window.Alpine) {
        window.Alpine.__initialized = true;
    }
    
    // Monitorar carregamentos adicionais do Alpine.js
    const originalDefineProperty = Object.defineProperty;
    Object.defineProperty = function(obj, prop, descriptor) {
        if (obj === window && prop === 'Alpine' && window.Alpine) {
            console.warn('Tentativa de redefinir o Alpine.js detectada. Isso pode causar problemas.');
            console.trace('Rastreamento da tentativa de redefinição do Alpine.js:');
            return window.Alpine;
        }
        return originalDefineProperty.call(this, obj, prop, descriptor);
    };
});
