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
                                                                @if($item->product->is_digital)
                                                                    <span class="ml-1 inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900 px-1.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-100">
                                                                        <flux:icon name="document" class="h-3 w-3 mr-0.5" />
                                                                        DIGITAL
                                                                    </span>
                                                                @endif
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

                    @php
                        $hasDigitalProducts = $order->items->contains(function ($item) {
                            return $item->product->is_digital;
                        });

                        $hasPhysicalProducts = $order->items->contains(function ($item) {
                            return !$item->product->is_digital;
                        });
                    @endphp

                    @if($hasDigitalProducts && in_array($order->status, ['processing', 'completed', 'delivered']))
                    <!-- Produtos Digitais -->
                    <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg mb-6">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <flux:icon name="document" class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" />
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Produtos Digitais</h3>
                            </div>

                            <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-md">
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Este pedido contém produtos digitais que estão disponíveis para download.
                                </p>
                                <div class="mt-3">
                                    <flux:button :href="route('shop.downloads')" size="sm" color="blue">
                                        <flux:icon name="arrow-down-tray" class="h-4 w-4 mr-1" />
                                        Acessar Meus Downloads
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($hasPhysicalProducts)
                    <!-- Informações de Entrega -->
                    <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Informações de Entrega</h3>

                            @if($order->shipping_address)
                                @if(isset($order->shipping_address['pickup']) && $order->shipping_address['pickup'])
                                    <div class="flex items-center">
                                        <flux:icon name="map-pin" class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" />
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Retirada no local</span>
                                    </div>
                                    @if(isset($order->shipping_address['message']))
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->shipping_address['message'] }}
                                        </p>
                                    @endif
                                @elseif(isset($order->shipping_address['address']))
                                    <address class="not-italic text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->shipping_address['address'] ?? 'Endereço não informado' }}<br>
                                        {{ $order->shipping_address['city'] ?? '' }} {{ isset($order->shipping_address['state']) ? '- '.$order->shipping_address['state'] : '' }}<br>
                                        {{ $order->shipping_address['zip_code'] ?? '' }}{{ isset($order->shipping_address['country']) ? ', '.$order->shipping_address['country'] : '' }}<br>
                                        @if(isset($order->shipping_address['phone']))
                                            <span class="font-medium text-gray-900 dark:text-white">Telefone:</span> {{ $order->shipping_address['phone'] }}
                                        @endif
                                    </address>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Informações de entrega não disponíveis.</p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma informação de entrega disponível.</p>
                            @endif
                        </div>
                    </div>
                    @endif
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

                            @php
                                $hasDigitalProducts = $order->items->contains(function ($item) {
                                    return $item->product->is_digital;
                                });
                            @endphp

                            @if($hasDigitalProducts && in_array($order->status, ['processing', 'completed', 'delivered']))
                                <div class="mt-6">
                                    <flux:button :href="route('shop.downloads')" class="w-full" color="blue">
                                        <flux:icon name="arrow-down-tray" class="h-5 w-5 mr-2" />
                                        Acessar Meus Downloads
                                    </flux:button>
                                </div>
                            @endif

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
