/**
 * Cart Debug
 * 
 * Este script ajuda a depurar problemas com o carrinho de compras
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('[Cart Debug] Inicializando script de depuração do carrinho...');
    
    // Verificar se estamos na página de detalhes do produto
    if (window.location.pathname.includes('/loja/produto/')) {
        console.log('[Cart Debug] Página de detalhes do produto detectada');
        
        // Aguardar um pouco para garantir que o Livewire tenha inicializado
        setTimeout(() => {
            const addToCartButton = document.getElementById('add-to-cart-button');
            
            if (addToCartButton) {
                console.log('[Cart Debug] Botão de adicionar ao carrinho encontrado:', addToCartButton);
                
                // Adicionar um evento de clique para depuração
                addToCartButton.addEventListener('click', function(event) {
                    console.log('[Cart Debug] Botão de adicionar ao carrinho clicado');
                    console.log('[Cart Debug] Event:', event);
                    
                    // Não impedir o comportamento padrão para permitir que o Livewire processe o evento
                });
            } else {
                console.warn('[Cart Debug] Botão de adicionar ao carrinho não encontrado');
            }
        }, 1000);
    }
    
    // Monitorar eventos do Livewire
    document.addEventListener('livewire:initialized', () => {
        console.log('[Cart Debug] Livewire inicializado');
        
        // Monitorar eventos relacionados ao carrinho
        window.Livewire.hook('message.sent', (message) => {
            console.log('[Cart Debug] Livewire message sent:', message);
        });
        
        window.Livewire.hook('message.failed', (message, error) => {
            console.error('[Cart Debug] Livewire message failed:', message, error);
        });
        
        window.Livewire.hook('message.received', (message) => {
            console.log('[Cart Debug] Livewire message received:', message);
        });
        
        window.Livewire.hook('message.processed', (message) => {
            console.log('[Cart Debug] Livewire message processed:', message);
        });
    });
});
