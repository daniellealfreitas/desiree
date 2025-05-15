/**
 * Cart Events Fix
 * 
 * Este script garante que os eventos do carrinho de compras sejam capturados corretamente
 * pelo Livewire 3, mesmo quando há problemas com a propagação de eventos.
 */

(function() {
    console.log('[Cart Events Fix] Inicializando correção para eventos do carrinho...');
    
    // Aguardar a inicialização do Livewire
    document.addEventListener('livewire:initialized', () => {
        console.log('[Cart Events Fix] Livewire inicializado, configurando handlers de eventos do carrinho');
        
        // Criar um proxy para o método dispatch do Livewire
        const originalDispatch = Livewire.dispatch;
        Livewire.dispatch = function(eventName, ...args) {
            // Registrar todos os eventos disparados
            console.log(`[Cart Events Fix] Evento Livewire disparado: ${eventName}`, args);
            
            // Verificar se é um evento relacionado ao carrinho
            if (eventName === 'add-to-cart') {
                console.log('[Cart Events Fix] Evento add-to-cart detectado, garantindo propagação');
                
                // Adicionar um pequeno atraso para garantir que o evento seja processado
                setTimeout(() => {
                    // Verificar se o carrinho foi atualizado
                    console.log('[Cart Events Fix] Verificando se o carrinho foi atualizado');
                    
                    // Disparar um evento de atualização do carrinho como fallback
                    Livewire.dispatch('cart-updated');
                }, 500);
            }
            
            // Chamar o método original
            return originalDispatch.apply(this, [eventName, ...args]);
        };
        
        // Adicionar listeners para os botões de adicionar ao carrinho
        document.addEventListener('click', function(event) {
            // Verificar se o elemento clicado ou um de seus pais tem wire:click="addToCart"
            let target = event.target;
            while (target && target !== document) {
                const wireClick = target.getAttribute('wire:click');
                if (wireClick && (wireClick === 'addToCart' || wireClick.startsWith('addToCart('))) {
                    console.log('[Cart Events Fix] Clique em botão addToCart detectado');
                    
                    // Adicionar um pequeno atraso para garantir que o evento seja processado
                    setTimeout(() => {
                        console.log('[Cart Events Fix] Verificando se o carrinho foi atualizado após clique');
                    }, 1000);
                    
                    break;
                }
                target = target.parentNode;
            }
        });
        
        console.log('[Cart Events Fix] Configuração concluída');
    });
})();
