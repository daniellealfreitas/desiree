/**
 * Direct Cart Handler
 *
 * Este script fornece uma maneira alternativa de adicionar produtos ao carrinho
 * sem depender dos eventos Livewire, usando requisições AJAX diretas.
 */

(function () {
    console.log('[Direct Cart Handler] Inicializando...');

    // Aguardar a inicialização do DOM
    document.addEventListener('DOMContentLoaded', () => {
        console.log('[Direct Cart Handler] DOM carregado, configurando handlers');
        setupDirectCartHandlers();
    });

    // Aguardar a inicialização do Livewire
    document.addEventListener('livewire:initialized', () => {
        console.log('[Direct Cart Handler] Livewire inicializado, configurando handlers');
        setupDirectCartHandlers();
    });

    /**
     * Configura os handlers para os botões de adicionar ao carrinho
     */
    function setupDirectCartHandlers() {
        // Adicionar atributo data-direct-cart aos botões de adicionar ao carrinho
        const addToCartButtons = document.querySelectorAll('[wire\\:click*="addToCart"]');

        addToCartButtons.forEach(button => {
            if (!button.hasAttribute('data-direct-cart')) {
                button.setAttribute('data-direct-cart', 'true');
                console.log('[Direct Cart Handler] Botão encontrado:', button);

                // Adicionar evento de clique
                button.addEventListener('click', handleDirectAddToCart);
            }
        });

        console.log('[Direct Cart Handler] Configuração concluída, encontrados ' + addToCartButtons.length + ' botões');
    }

    /**
     * Manipula o clique no botão de adicionar ao carrinho
     */
    function handleDirectAddToCart(event) {
        // Impedir o comportamento padrão para evitar que o Livewire processe o evento
        event.preventDefault();
        event.stopPropagation();

        const button = event.currentTarget;
        console.log('[Direct Cart Handler] Clique detectado:', button);

        // Extrair o ID do produto do atributo wire:click
        const wireClick = button.getAttribute('wire:click');
        let productId = null;
        let quantity = 1;

        if (wireClick === 'addToCart') {
            // Estamos na página de detalhes do produto
            // Precisamos obter o ID do produto da URL e a quantidade selecionada
            const urlParts = window.location.pathname.split('/');
            const productSlug = urlParts[urlParts.length - 1];

            // Tentar obter a quantidade do input, se existir
            const quantityInput = document.querySelector('input[name="quantity"]');
            if (quantityInput) {
                quantity = parseInt(quantityInput.value) || 1;
            }

            // Fazer uma requisição para obter o ID do produto pelo slug
            fetch(`/api/products/slug/${productSlug}`)
                .then(response => response.json())
                .then(data => {
                    if (data.id) {
                        addToCartDirectly(data.id, quantity);
                    }
                })
                .catch(error => {
                    console.error('[Direct Cart Handler] Erro ao obter ID do produto:', error);
                });

            return;
        } else if (wireClick.startsWith('addToCart(')) {
            // Extrair o ID do produto
            const match = wireClick.match(/addToCart\((\d+)\)/);
            if (match && match[1]) {
                productId = parseInt(match[1]);
            }
        }

        if (productId) {
            addToCartDirectly(productId, quantity);
        } else {
            console.error('[Direct Cart Handler] Não foi possível extrair o ID do produto:', wireClick);

            // Permitir que o Livewire processe o evento como fallback
            Livewire.dispatch('add-to-cart', {
                productId: productId,
                quantity: quantity,
                price: 0 // Será atualizado pelo backend
            });
        }
    }

    /**
     * Adiciona um produto ao carrinho diretamente via AJAX
     */
    function addToCartDirectly(productId, quantity) {
        console.log('[Direct Cart Handler] Adicionando produto ao carrinho:', productId, quantity);

        if (!productId) {
            console.error('[Direct Cart Handler] ID do produto inválido');
            showNotification('Erro ao adicionar produto ao carrinho: ID do produto inválido', 'error');
            return;
        }

        // Obter o token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Fazer a requisição AJAX
        fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity || 1
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('[Direct Cart Handler] Resposta:', data);

                if (data.success) {
                    // Mostrar notificação de sucesso com o novo tipo 'cart'
                    showNotification('Produto adicionado ao carrinho!', 'cart');

                    // Atualizar o carrinho
                    if (typeof Livewire !== 'undefined') {
                        Livewire.dispatch('cart-updated');
                    }

                    // Redirecionar para a página do carrinho após um breve atraso
                    setTimeout(() => {
                        window.location.href = '/loja/carrinho';
                    }, 1000);
                } else {
                    // Mostrar notificação de erro
                    showNotification(data.message || 'Erro ao adicionar produto ao carrinho', 'error');
                }
            })
            .catch(error => {
                console.error('[Direct Cart Handler] Erro:', error);
                showNotification('Erro ao adicionar produto ao carrinho: ' + error.message, 'error');

                // Tentar usar o Livewire como fallback
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('add-to-cart', {
                        productId: productId,
                        quantity: quantity || 1,
                        price: 0 // Será atualizado pelo backend
                    });
                }
            });
    }

    /**
     * Mostra uma notificação na tela
     */
    function showNotification(message, type) {
        // Tentar usar o sistema de notificações do Livewire
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('notify', {
                message: message,
                type: type,
                timeout: type === 'cart' ? 8000 : 3000 // Tempo maior para notificações do carrinho
            });
            return;
        }

        // Fallback para notificação simples
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-md ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
})();
