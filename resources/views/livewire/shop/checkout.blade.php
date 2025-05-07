<div>
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Finalizar Compra</h1>

            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-12">
                <!-- Formulário de Checkout -->
                <div class="lg:col-span-8">
                    <div class="space-y-8">
                        <!-- Endereço de Entrega -->
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Endereço de Entrega</h2>

                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <flux:input
                                        wire:model="address"
                                        label="Endereço completo"
                                        placeholder="Rua, número, complemento, bairro"
                                        required
                                    />
                                </div>

                                <div>
                                    <flux:input
                                        wire:model="city"
                                        label="Cidade"
                                        placeholder="Sua cidade"
                                        required
                                    />
                                </div>

                                <div>
                                    <flux:input
                                        wire:model="state"
                                        label="Estado"
                                        placeholder="Seu estado"
                                        required
                                    />
                                </div>

                                <div>
                                    <flux:input
                                        wire:model="zipCode"
                                        label="CEP"
                                        placeholder="00000-000"
                                        required
                                    />
                                </div>

                                <div>
                                    <flux:input
                                        wire:model="country"
                                        label="País"
                                        placeholder="Seu país"
                                        required
                                    />
                                </div>

                                <div class="sm:col-span-2">
                                    <flux:input
                                        wire:model="phone"
                                        label="Telefone"
                                        placeholder="(00) 00000-0000"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Método de Pagamento -->
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Método de Pagamento</h2>

                            <div class="mt-4">
                                <flux:radio.group wire:model.live="paymentMethod" variant="segmented">
                                    <flux:radio value="credit_card">Cartão de Crédito</flux:radio>
                                    <flux:radio value="pix">PIX</flux:radio>
                                    <flux:radio value="boleto">Boleto</flux:radio>
                                </flux:radio.group>

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
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            Após confirmar o pedido, você receberá um QR Code para pagamento via PIX.
                                        </p>
                                    </div>
                                @endif

                                <!-- Boleto -->
                                @if($paymentMethod === 'boleto')
                                    <div class="mt-4 bg-gray-50 dark:bg-zinc-900 p-4 rounded-md">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            Após confirmar o pedido, você receberá um boleto para pagamento.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Observações</h2>

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
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Resumo do Pedido</h2>

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
                                            <div class="flex justify-between text-sm font-medium text-gray-900 dark:text-white">
                                                <h3>{{ $item->product->name }}</h3>
                                                <p class="ml-4">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</p>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Qtd: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
                                <div class="flex justify-between">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Subtotal</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">R$ {{ number_format($cart->total, 2, ',', '.') }}</p>
                                </div>

                                @if($cart->discount > 0)
                                    <div class="flex justify-between">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Desconto</p>
                                        <p class="text-sm font-medium text-green-600 dark:text-green-400">- R$ {{ number_format($cart->discount, 2, ',', '.') }}</p>
                                    </div>
                                @endif

                                <div class="flex justify-between">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Frete</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Grátis</p>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between">
                                    <p class="text-base font-medium text-gray-900 dark:text-white">Total</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-white">R$ {{ number_format($cart->getTotalWithDiscount(), 2, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <flux:button
                                id="checkout-button"
                                wire:loading.attr="disabled"
                                wire:target="placeOrder"
                                class="w-full"
                            >
                                <span wire:loading.remove wire:target="placeOrder">Finalizar Pedido</span>
                                <span wire:loading wire:target="placeOrder">Processando...</span>
                            </flux:button>

                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-center">
                                Ao finalizar o pedido, você concorda com nossos <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Termos e Condições</a>.
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
    document.addEventListener('livewire:initialized', () => {
        // Inicializar Stripe
        const stripe = Stripe('{{ config('cashier.key') }}');
        const elements = stripe.elements();

        // Criar elemento de cartão
        const cardElement = elements.create('card', {
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

        // Montar elemento de cartão
        cardElement.mount('#card-element');

        // Lidar com erros de validação em tempo real
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Lidar com o envio do formulário
        const checkoutButton = document.getElementById('checkout-button');
        checkoutButton.addEventListener('click', async (e) => {
            e.preventDefault();

            // Desabilitar o botão durante o processamento
            checkoutButton.disabled = true;

            // Verificar o método de pagamento selecionado
            const paymentMethod = @this.paymentMethod;

            if (paymentMethod === 'credit_card') {
                // Criar token do cartão
                const { token, error } = await stripe.createToken(cardElement);

                if (error) {
                    // Exibir erro e reativar o botão
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                    checkoutButton.disabled = false;
                } else {
                    // Enviar o token para o servidor
                    @this.set('stripeToken', token.id);
                    @this.placeOrder();
                }
            } else {
                // Para outros métodos de pagamento, apenas chamar placeOrder
                @this.placeOrder();
            }
        });
    });
</script>
@endpush