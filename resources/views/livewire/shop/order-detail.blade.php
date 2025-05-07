<div>
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Detalhes do Pedido #{{ $order->id }}</h1>
                <flux:button :href="route('shop.user.orders')" variant="outline" size="sm">
                    <flux:icon name="arrow-left" class="h-4 w-4 mr-2" />
                    Voltar para Meus Pedidos
                </flux:button>
            </div>

            <!-- Status do Pedido -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Status do Pedido</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                Pedido realizado em {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $this->getStatusClass($order->status) }}">
                            {{ $this->getStatusName($order->status) }}
                        </span>
                    </div>

                    @if($order->tracking_number)
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-zinc-900 rounded-md">
                            <div class="flex items-center">
                                <flux:icon name="truck" class="h-5 w-5 text-gray-400 mr-2" />
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Código de Rastreio:</span>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">{{ $order->tracking_number }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Informações do Pedido -->
                <div class="lg:col-span-2">
                    <!-- Itens do Pedido -->
                    <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg mb-6">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Itens do Pedido</h3>

                            <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-zinc-900">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produto</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Preço</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qtd</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="h-10 w-10 flex-shrink-0">
                                                            <img class="h-10 w-10 rounded-md object-cover" src="{{ $item->product->getImageUrl() ?? 'https://placehold.co/100x100/e2e8f0/1e293b?text=Sem+Imagem' }}" alt="{{ $item->product->name }}">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                {{ $item->product->name }}
                                                            </div>
                                                            @if(!empty($item->options))
                                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                    @foreach($item->options as $option => $value)
                                                                        <span>{{ ucfirst($option) }}: {{ $value }}</span>
                                                                        @if(!$loop->last) | @endif
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    R$ {{ number_format($item->price, 2, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                                    R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço de Entrega -->
                    <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Endereço de Entrega</h3>

                            @if($order->shipping_address)
                                <address class="not-italic text-sm text-gray-500 dark:text-gray-400">
                                    {{ $order->shipping_address['address'] }}<br>
                                    {{ $order->shipping_address['city'] }} - {{ $order->shipping_address['state'] }}<br>
                                    {{ $order->shipping_address['zip_code'] }}, {{ $order->shipping_address['country'] }}<br>
                                    <span class="font-medium text-gray-900 dark:text-white">Telefone:</span> {{ $order->shipping_address['phone'] }}
                                </address>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum endereço de entrega informado.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Resumo do Pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg sticky top-4">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Resumo do Pedido</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Subtotal</span>
                                    <span class="text-sm text-gray-900 dark:text-white">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                                </div>

                                @if($order->discount > 0)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Desconto</span>
                                        <span class="text-sm text-green-600 dark:text-green-400">- R$ {{ number_format($order->discount, 2, ',', '.') }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Frete</span>
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        @if($order->shipping_cost > 0)
                                            R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}
                                        @else
                                            Grátis
                                        @endif
                                    </span>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between">
                                    <span class="text-base font-medium text-gray-900 dark:text-white">Total</span>
                                    <span class="text-base font-medium text-gray-900 dark:text-white">R$ {{ number_format($order->getFinalTotal(), 2, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="mt-6 space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Método de Pagamento</h4>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->payment_method === 'credit_card' ? 'Cartão de Crédito' :
                                           ($order->payment_method === 'pix' ? 'PIX' :
                                           ($order->payment_method === 'boleto' ? 'Boleto' : ucfirst($order->payment_method))) }}
                                    </p>
                                </div>

                                @if($order->payment_id)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">ID do Pagamento</h4>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->payment_id }}
                                        </p>
                                    </div>
                                @endif

                                @if($order->notes)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Observações</h4>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 whitespace-pre-line">
                                            {{ $order->notes }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            @if($order->status === 'pending' || $order->status === 'processing')
                                <div class="mt-6">
                                    <flux:button :href="route('shop.index')" class="w-full">
                                        <flux:icon name="shopping-bag" class="h-5 w-5 mr-2" />
                                        Continuar Comprando
                                    </flux:button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
