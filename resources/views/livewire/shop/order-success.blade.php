@php
    use Illuminate\Support\Str;
@endphp

<div>
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <div class="text-center">
                <div class="flex justify-center">
                    <div class="h-24 w-24 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                        <flux:icon name="check" class="h-12 w-12 text-green-600 dark:text-green-400" />
                    </div>
                </div>
                <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Pedido Realizado com Sucesso!</h1>
                <p class="mt-2 text-lg text-gray-500 dark:text-gray-400">
                    Seu pedido #{{ $order->id }} foi recebido e está sendo processado.
                </p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Um e-mail de confirmação foi enviado para {{ auth()->user()->email }}.
                </p>
            </div>

            <div class="mt-12 border-t border-gray-200 dark:border-gray-700 pt-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Resumo do Pedido</h2>

                <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gray-50 dark:bg-zinc-900 px-4 py-5 sm:px-6">
                        <div class="flex justify-between">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Pedido #{{ $order->id }}</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                    Realizado em {{ $order->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                    {{ $order->status === 'pending' ? 'Pendente' : ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Método de Pagamento</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @switch($order->payment_method)
                                        @case('credit_card')
                                            Cartão de Crédito
                                            @break
                                        @case('wallet')
                                            Carteira
                                            @break
                                        @case('pix')
                                            PIX
                                            @break
                                        @case('boleto')
                                            Boleto
                                            @break
                                        @default
                                            {{ ucfirst($order->payment_method) }}
                                    @endswitch
                                </dd>
                            </div>

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID do Pagamento</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @if(Str::startsWith($order->payment_id, 'WALLET-'))
                                        <span class="flex items-center">
                                            <x-flux::icon name="wallet" class="h-4 w-4 text-green-500 mr-1" />
                                            {{ $order->payment_id }}
                                        </span>
                                    @else
                                        {{ $order->payment_id ?? 'N/A' }}
                                    @endif
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Informações de Entrega</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @if($order->shipping_address && isset($order->shipping_address['pickup']) && $order->shipping_address['pickup'])
                                        <div class="flex items-center">
                                            <flux:icon name="map-pin" class="h-4 w-4 text-blue-600 dark:text-blue-400 mr-2" />
                                            <span>Retirada no local</span>
                                        </div>
                                        @if(isset($order->shipping_address['message']))
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $order->shipping_address['message'] }}
                                            </p>
                                        @endif
                                    @elseif($order->shipping_address && isset($order->shipping_address['address']))
                                        <address class="not-italic">
                                            {{ $order->shipping_address['address'] ?? 'Endereço não informado' }}<br>
                                            {{ $order->shipping_address['city'] ?? '' }} {{ isset($order->shipping_address['state']) ? '- '.$order->shipping_address['state'] : '' }}<br>
                                            {{ $order->shipping_address['zip_code'] ?? '' }}{{ isset($order->shipping_address['country']) ? ', '.$order->shipping_address['country'] : '' }}<br>
                                            @if(isset($order->shipping_address['phone']))
                                                <span class="text-gray-500 dark:text-gray-400">Telefone:</span> {{ $order->shipping_address['phone'] }}
                                            @endif
                                        </address>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Não informado</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="overflow-hidden">
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
                                <tfoot class="bg-gray-50 dark:bg-zinc-900">
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Subtotal:</td>
                                        <td class="px-6 py-3 text-right text-sm text-gray-500 dark:text-gray-400">
                                            R$ {{ number_format($order->total, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    @if($order->discount > 0)
                                        <tr>
                                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Desconto:</td>
                                            <td class="px-6 py-3 text-right text-sm text-green-600 dark:text-green-400">
                                                - R$ {{ number_format($order->discount, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Frete:</td>
                                        <td class="px-6 py-3 text-right text-sm text-gray-500 dark:text-gray-400">
                                            @if($order->shipping_cost > 0)
                                                R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}
                                            @else
                                                Grátis
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">Total:</td>
                                        <td class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">
                                            R$ {{ number_format($order->getFinalTotal(), 2, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção de produtos digitais -->
            @php
                $hasDigitalProducts = $order->items->contains(function ($item) {
                    return $item->product->is_digital;
                });
            @endphp

            @if($hasDigitalProducts)
                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
                        <div class="flex items-start">
                            <flux:icon name="information-circle" class="h-6 w-6 text-blue-600 dark:text-blue-400 mr-3 mt-0.5" />
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Produtos Digitais</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Seu pedido contém produtos digitais que estarão disponíveis para download após a confirmação do pagamento.
                                </p>
                                <div class="mt-4">
                                    <flux:button :href="route('shop.downloads')" variant="outline" color="blue">
                                        <flux:icon name="arrow-down-tray" class="h-5 w-5 mr-2" />
                                        Acessar Meus Downloads
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-8 flex justify-center space-x-4">
                <flux:button :href="route('shop.user.orders')" variant="outline">
                    <flux:icon name="clipboard-document-list" class="h-5 w-5 mr-2" />
                    Meus Pedidos
                </flux:button>
                <flux:button :href="route('shop.index')">
                    <flux:icon name="shopping-bag" class="h-5 w-5 mr-2" />
                    Continuar Comprando
                </flux:button>
            </div>
        </div>
    </div>
</div>
