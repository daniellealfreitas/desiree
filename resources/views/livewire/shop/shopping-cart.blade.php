<div>
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Carrinho de Compras</h1>
            
            @if($cart->items->isEmpty())
                <div class="mt-12 text-center py-12 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                    <flux:icon name="shopping-cart" class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Seu carrinho está vazio</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece a adicionar produtos para continuar suas compras.</p>
                    <div class="mt-6">
                        <flux:button :href="route('shop.index')" variant="primary">
                            Continuar Comprando
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-12">
                    <!-- Itens do Carrinho -->
                    <div class="lg:col-span-8">
                        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-zinc-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produto</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Preço</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantidade</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subtotal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($cart->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
                                                        <img 
                                                            src="{{ $item->product->getImageUrl() ?? 'https://placehold.co/600x600/e2e8f0/1e293b?text=Sem+Imagem' }}" 
                                                            alt="{{ $item->product->name }}" 
                                                            class="h-full w-full object-cover object-center"
                                                        >
                                                    </div>
                                                    <div class="ml-4">
                                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                                            <a href="{{ route('shop.product', $item->product->slug) }}">
                                                                {{ $item->product->name }}
                                                            </a>
                                                        </h3>
                                                        @if(!empty($item->options))
                                                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
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
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <flux:button 
                                                        wire:click="updateQuantity({{ $item->id }}, {{ max(1, $item->quantity - 1) }})" 
                                                    
                                                        size="xs"
                                                    >
                                                        <flux:icon name="minus" class="h-3 w-3" />
                                                    </flux:button>
                                                    <span class="mx-2 text-sm text-gray-700 dark:text-gray-300">{{ $item->quantity }}</span>
                                                    <flux:button 
                                                        wire:click="updateQuantity({{ $item->id }}, {{ min($item->product->stock, $item->quantity + 1) }})" 
                                                    
                                                        size="xs"
                                                    >
                                                        <flux:icon name="plus" class="h-3 w-3" />
                                                    </flux:button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <flux:button 
                                                    wire:click="removeItem({{ $item->id }})" 
                                                    variant="ghost" 
                                                    size="xs"
                                                >
                                                    <flux:icon name="trash" class="h-4 w-4 text-red-500" />
                                                </flux:button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4 flex justify-between">
                            <flux:button :href="route('shop.index')" >
                                <flux:icon name="arrow-left" class="h-4 w-4 mr-1" />
                                Continuar Comprando
                            </flux:button>
                            
                            <flux:button wire:click="clearCart" >
                                <flux:icon name="trash" class="h-4 w-4 mr-1" />
                                Limpar Carrinho
                            </flux:button>
                        </div>
                    </div>
                    
                    <!-- Resumo do Pedido -->
                    <div class="lg:col-span-4">
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-zinc-800 p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Resumo do Pedido</h2>
                            
                            <div class="mt-6 space-y-4">
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
                                
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between">
                                    <p class="text-base font-medium text-gray-900 dark:text-white">Total</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-white">R$ {{ number_format($cart->getTotalWithDiscount(), 2, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <!-- Cupom -->
                            <div class="mt-6">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Cupom de Desconto</h3>
                                
                                @if($cart->coupon_id)
                                    <div class="mt-2 flex items-center justify-between bg-green-50 dark:bg-green-900 p-3 rounded-md">
                                        <div>
                                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                                Cupom aplicado: {{ $cart->coupon->code }}
                                            </p>
                                            <p class="text-xs text-green-700 dark:text-green-300">
                                                Desconto: R$ {{ number_format($cart->discount, 2, ',', '.') }}
                                            </p>
                                        </div>
                                        <flux:button wire:click="removeCoupon" variant="ghost" size="xs">
                                            <flux:icon name="x-mark" class="h-4 w-4 text-green-700 dark:text-green-300" />
                                        </flux:button>
                                    </div>
                                @else
                                    <div class="mt-2 flex">
                                        <flux:input wire:model="couponCode" placeholder="Código do cupom" class="flex-1" />
                                        <flux:button wire:click="applyCoupon" class="ml-2">Aplicar</flux:button>
                                    </div>
                                    
                                    @if($couponError)
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $couponError }}</p>
                                    @endif
                                    
                                    @if($couponSuccess)
                                        <p class="mt-1 text-xs text-green-600 dark:text-green-400">{{ $couponSuccess }}</p>
                                    @endif
                                @endif
                            </div>
                            
                            <div class="mt-6">
                                <flux:button wire:click="checkout" class="w-full">
                                    Finalizar Compra
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
