<div>
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-title">Finalizar Compra</h1>

            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-12">
                <!-- Formulário de Checkout -->
                <div class="lg:col-span-8">
                    <div class="space-y-8">
                        <!-- Informação sobre retirada no local -->
                        @if($hasPhysicalProducts)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-6 bg-blue-50 dark:bg-blue-900/20">
                            <div class="flex items-start">
                                <flux:icon name="information-circle" class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2 mt-0.5" />
                                <div>
                                    <h2 class="text-lg font-medium text-title">Retirada no Local</h2>
                                    <p class="mt-1 text-sm text-body-light">
                                        Os produtos físicos deverão ser retirados em nossa loja. Após a confirmação do pagamento, você receberá instruções sobre como proceder com a retirada.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Informações sobre produtos digitais -->
                        @if($hasDigitalProducts)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-6 bg-blue-50 dark:bg-blue-900/20">
                            <div class="flex items-start">
                                <flux:icon name="information-circle" class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2 mt-0.5" />
                                <div>
                                    <h2 class="text-lg font-medium text-title">Produtos Digitais</h2>
                                    <p class="mt-1 text-sm text-body-light">
                                        Seu pedido contém produtos digitais que estarão disponíveis para download imediatamente após a confirmação do pagamento.
                                    </p>
                                    <ul class="mt-2 text-sm text-body-light list-disc pl-5 space-y-1">
                                        <li>Acesse seus downloads na área "Meus Downloads" após a compra</li>
                                        <li>Não é necessário endereço de entrega para produtos digitais</li>
                                        <li>Alguns arquivos podem ter limite de downloads ou prazo de expiração</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Método de Pagamento -->
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                            <h2 class="text-lg font-medium text-title">Método de Pagamento</h2>

                            <div class="mt-4">
                                <div class="flex flex-wrap gap-2">
                                    <button type="button"
                                        class="px-4 py-2 rounded-md {{ $paymentMethod === 'credit_card' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}"
                                        wire:click="$set('paymentMethod', 'credit_card')">
                                        Cartão de Crédito
                                    </button>
                                    <button type="button"
                                        class="px-4 py-2 rounded-md {{ $paymentMethod === 'wallet' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}"
                                        wire:click="$set('paymentMethod', 'wallet')">
                                        Carteira (R$ {{ number_format($walletBalance, 2, ',', '.') }})
                                    </button>
                                    <button type="button"
                                        class="px-4 py-2 rounded-md {{ $paymentMethod === 'pix' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}"
                                        wire:click="$set('paymentMethod', 'pix')">
                                        PIX
                                    </button>
                                    <button type="button"
                                        class="px-4 py-2 rounded-md {{ $paymentMethod === 'boleto' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}"
                                        wire:click="$set('paymentMethod', 'boleto')">
                                        Boleto
                                    </button>
                                </div>

                                <!-- Cartão de Crédito (Stripe) -->
                                @if($paymentMethod === 'credit_card')
                                    <div class="mt-4">
                                        <div id="card-element" class="p-3 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-900">
                                            <!-- Stripe Elements será inserido aqui -->
                                        </div>
                                        <div id="card-errors" class="mt-2 text-sm text-red-600 dark:text-red-400" role="alert"></div>
                                    </div>
                                @endif

                                <!-- PIX -->
                                @if($paymentMethod === 'pix')
                                    <div class="mt-4 bg-gray-50 dark:bg-zinc-900 p-4 rounded-md">
                                        <p class="text-sm text-body">
                                            Após confirmar o pedido, você receberá um QR Code para pagamento via PIX.
                                        </p>
                                    </div>
                                @endif

                                <!-- Boleto -->
                                @if($paymentMethod === 'boleto')
                                    <div class="mt-4 bg-gray-50 dark:bg-zinc-900 p-4 rounded-md">
                                        <p class="text-sm text-body">
                                            Após confirmar o pedido, você receberá um boleto para pagamento.
                                        </p>
                                    </div>
                                @endif

                                <!-- Carteira -->
                                @if($paymentMethod === 'wallet')
                                    <div class="mt-4 bg-gray-50 dark:bg-zinc-900 p-4 rounded-md">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-sm font-medium text-body">
                                                Saldo disponível:
                                            </p>
                                            <div class="flex items-center">
                                                <p class="text-sm font-medium {{ $walletBalance >= $cart->getTotalWithDiscount() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    R$ {{ number_format($walletBalance, 2, ',', '.') }}
                                                </p>
                                                <button
                                                    type="button"
                                                    wire:click="refreshWalletBalance"
                                                    wire:loading.attr="disabled"
                                                    wire:target="refreshWalletBalance"
                                                    class="ml-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                                    title="Atualizar saldo"
                                                >
                                                    <x-flux::icon name="arrow-path" class="h-4 w-4" wire:loading.class="animate-spin" wire:target="refreshWalletBalance" />
                                                </button>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-sm font-medium text-body">
                                                Valor da compra:
                                            </p>
                                            <p class="text-sm font-medium text-body">
                                                R$ {{ number_format($cart->getTotalWithDiscount(), 2, ',', '.') }}
                                            </p>
                                        </div>

                                        @if($walletBalance < $cart->getTotalWithDiscount())
                                            <div class="mt-2 p-2 bg-red-50 dark:bg-red-900/30 rounded text-sm text-red-600 dark:text-red-400">
                                                <p class="flex items-center">
                                                    <x-flux::icon name="exclamation-triangle" class="h-4 w-4 mr-1" />
                                                    Saldo insuficiente. Adicione fundos à sua carteira ou escolha outro método de pagamento.
                                                </p>
                                                <div class="mt-1 flex space-x-3">
                                                    <a href="{{ route('wallet.add-funds') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                                                        <x-flux::icon name="plus" class="h-3 w-3 mr-1" />
                                                        Adicionar fundos
                                                    </a>
                                                    <button
                                                        type="button"
                                                        wire:click="refreshWalletBalance"
                                                        wire:loading.attr="disabled"
                                                        wire:target="refreshWalletBalance"
                                                        class="text-blue-600 dark:text-blue-400 hover:underline flex items-center"
                                                    >
                                                        <x-flux::icon name="arrow-path" class="h-3 w-3 mr-1" wire:loading.class="animate-spin" wire:target="refreshWalletBalance" />
                                                        Atualizar saldo
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                            <h2 class="text-lg font-medium text-title">Observações</h2>

                            <div class="mt-4">
                                <flux:textarea
                                    wire:model="notes"
                                    placeholder="Informações adicionais sobre o pedido ou entrega"
                                    rows="3"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumo do Pedido -->
                <div class="lg:col-span-4">
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-zinc-800 p-6 sticky top-4">
                        <h2 class="text-lg font-medium text-title">Resumo do Pedido</h2>

                        <div class="mt-6 space-y-4">
                            @foreach($cart->items as $item)
                                <div class="flex">
                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
                                        <img
                                            src="{{ $item->product->getImageUrl() ?? 'https://placehold.co/600x600/e2e8f0/1e293b?text=Sem+Imagem' }}"
                                            alt="{{ $item->product->name }}"
                                            class="h-full w-full object-cover object-center"
                                        >
                                    </div>
                                    <div class="ml-4 flex flex-1 flex-col">
                                        <div>
                                            <div class="flex justify-between text-sm font-medium text-title">
                                                <h3>{{ $item->product->name }}</h3>
                                                <p class="ml-4">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</p>
                                            </div>
                                            <div class="mt-1 flex items-center">
                                                <p class="text-xs text-body-lighter">Qtd: {{ $item->quantity }}</p>
                                                @if($item->product->is_digital)
                                                    <span class="ml-2 inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900 px-1.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-100">
                                                        <flux:icon name="document" class="h-3 w-3 mr-0.5" />
                                                        Digital
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
                                <div class="flex justify-between">
                                    <p class="text-sm text-body-light">Subtotal</p>
                                    <p class="text-sm font-medium text-title">R$ {{ number_format($cart->total, 2, ',', '.') }}</p>
                                </div>

                                @if($cart->discount > 0)
                                    <div class="flex justify-between">
                                        <p class="text-sm text-body-light">Desconto</p>
                                        <p class="text-sm font-medium text-success">- R$ {{ number_format($cart->discount, 2, ',', '.') }}</p>
                                    </div>
                                @endif

                                @if($hasPhysicalProducts)
                                <div class="flex justify-between">
                                    <p class="text-sm text-body-light">Frete</p>
                                    <p class="text-sm font-medium text-title">Grátis</p>
                                </div>
                                @endif

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between">
                                    <p class="text-base font-medium text-title">Total</p>
                                    <p class="text-base font-medium text-title">R$ {{ number_format($cart->getTotalWithDiscount(), 2, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button
                                type="button"
                                id="checkout-button"
                                wire:click="placeOrder"
                                wire:loading.attr="disabled"
                                wire:target="placeOrder,refreshWalletBalance"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md flex items-center justify-center"
                                @if($paymentMethod === 'wallet' && $walletBalance < $cart->getTotalWithDiscount()) disabled @endif
                            >
                                <span wire:loading.remove wire:target="placeOrder">
                                    @if($paymentMethod === 'wallet' && $walletBalance < $cart->getTotalWithDiscount())
                                        Saldo Insuficiente
                                    @else
                                        Finalizar Pedido
                                    @endif
                                </span>
                                <span wire:loading wire:target="placeOrder">
                                    <x-flux::icon name="arrow-path" class="h-4 w-4 animate-spin mr-1" />
                                    Processando...
                                </span>
                            </button>

                            <p class="mt-2 text-xs text-body-lighter text-center">
                                Ao finalizar o pedido, você concorda com nossos <a href="#" class="text-link hover:underline">Termos e Condições</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Variável para controlar se o Stripe já foi inicializado
    let stripeInitialized = false;
    let cardElement = null;
    let stripe = null;

    // Função para inicializar o Stripe apenas quando necessário
    function initializeStripe() {
        if (stripeInitialized) return;

        // Inicializar Stripe
        stripe = Stripe('{{ config('cashier.key') }}');
        const elements = stripe.elements();

        // Criar elemento de cartão
        cardElement = elements.create('card', {
            style: {
                base: {
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: document.documentElement.classList.contains('dark') ? '#aab7c4' : '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            }
        });

        // Marcar como inicializado
        stripeInitialized = true;
    }

    // Função para montar o elemento de cartão
    function mountCardElement() {
        if (!cardElement) return;

        const cardElementContainer = document.getElementById('card-element');
        if (!cardElementContainer) return;

        // Verificar se o elemento já está montado
        try {
            cardElement.mount('#card-element');

            // Lidar com erros de validação em tempo real
            cardElement.on('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (!displayError) return;

                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        } catch (e) {
            console.log('Erro ao montar elemento de cartão:', e);
        }
    }

    document.addEventListener('livewire:initialized', () => {
        // Inicializar Stripe apenas quando necessário
        initializeStripe();

        // Configurar o botão de checkout
        const setupCheckoutButton = () => {
            const checkoutButton = document.getElementById('checkout-button');
            if (!checkoutButton) return;

            // Remover listeners anteriores para evitar duplicação
            const newCheckoutButton = checkoutButton.cloneNode(true);
            checkoutButton.parentNode.replaceChild(newCheckoutButton, checkoutButton);

            // Interceptar o clique no botão de checkout
            newCheckoutButton.addEventListener('click', async (e) => {
                // Verificar o método de pagamento selecionado
                const paymentMethod = @this.paymentMethod;

                // Se não for cartão de crédito, deixar o Livewire lidar com o clique normalmente
                if (paymentMethod !== 'credit_card') {
                    return;
                }

                // Para cartão de crédito, precisamos processar o token antes
                e.preventDefault();
                e.stopPropagation(); // Impedir que o evento se propague para o Livewire

                console.log('Interceptando clique para processamento de cartão de crédito');

                // Verificar se o botão já está desabilitado
                if (newCheckoutButton.disabled) {
                    console.log('Botão já está desabilitado, ignorando clique');
                    return;
                }

                // Desabilitar o botão durante o processamento
                newCheckoutButton.disabled = true;

                // Adicionar classe de loading para feedback visual
                newCheckoutButton.classList.add('opacity-75');

                if (!stripe || !cardElement) {
                    console.error('Stripe não inicializado corretamente');
                    newCheckoutButton.disabled = false;
                    newCheckoutButton.classList.remove('opacity-75');

                    Livewire.dispatch('notify', {
                        message: 'Erro ao inicializar o processador de pagamento. Por favor, recarregue a página e tente novamente.',
                        type: 'error'
                    });
                    return;
                }

                try {
                    console.log('Processando pagamento com cartão...');

                    // Criar token do cartão
                    const { token, error } = await stripe.createToken(cardElement);

                    if (error) {
                        // Exibir erro e reativar o botão
                        const errorElement = document.getElementById('card-errors');
                        if (errorElement) {
                            errorElement.textContent = error.message;
                        }
                        newCheckoutButton.disabled = false;
                        newCheckoutButton.classList.remove('opacity-75');

                        Livewire.dispatch('notify', {
                            message: 'Erro no cartão: ' + error.message,
                            type: 'error'
                        });
                    } else {
                        console.log('Token do cartão gerado com sucesso, enviando para o servidor...');

                        // Enviar o token para o servidor usando a sintaxe correta do Livewire 3
                        console.log('Enviando token para o servidor:', token.id);

                        // Usar a sintaxe correta do Livewire 3
                        const componentId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
                        Livewire.dispatch('setStripeToken', { token: token.id });

                        // Aguardar um momento para garantir que o token seja processado
                        setTimeout(() => {
                            console.log('Chamando placeOrder após envio do token');
                            // Chamar o método placeOrder diretamente no componente Livewire
                            Livewire.find(componentId).call('placeOrder');
                        }, 300);
                    }
                } catch (e) {
                    console.error('Erro ao processar pagamento com cartão:', e);
                    newCheckoutButton.disabled = false;
                    newCheckoutButton.classList.remove('opacity-75');

                    Livewire.dispatch('notify', {
                        message: 'Erro ao processar pagamento com cartão. Por favor, tente novamente.',
                        type: 'error'
                    });
                }
            });
        };

        // Configurar o botão inicialmente
        setupCheckoutButton();

        // Montar o elemento de cartão se necessário
        if (@this.paymentMethod === 'credit_card') {
            setTimeout(() => {
                mountCardElement();
            }, 100);
        }

        // Observar mudanças no método de pagamento
        document.addEventListener('livewire:paymentMethodChanged', (event) => {
            const method = event.detail;
            console.log('Método de pagamento alterado para:', method);

            if (method === 'credit_card') {
                setTimeout(() => {
                    mountCardElement();
                }, 100);
            }

            // Reconfigurar o botão após mudança de método
            setTimeout(() => {
                setupCheckoutButton();
            }, 200);
        });

        // Listener para quando o checkout falhar
        document.addEventListener('livewire:checkoutFailed', () => {
            console.log('Checkout falhou, reativando botão...');

            // Reativar o botão de checkout
            const checkoutButton = document.getElementById('checkout-button');
            if (checkoutButton) {
                checkoutButton.disabled = false;
                checkoutButton.classList.remove('opacity-75');
            }

            // Se estiver usando cartão de crédito, limpar o campo
            if (@this.$wire.paymentMethod === 'credit_card' && cardElement) {
                cardElement.clear();
            }
        });
    });
</script>
@endpush