<div>
    <flux:dropdown position="top" align="end">
        <div class="relative">
            <flux:navbar.item icon="shopping-cart" :badge="$itemCount > 0 ? $itemCount : null">
                <span class="hidden sm:inline">Carrinho</span>
                @if($totalAmount > 0)
                    <span class="ml-1 text-sm font-medium text-indigo-600 dark:text-indigo-400">
                        R$ {{ number_format($totalAmount, 2, ',', '.') }}
                    </span>
                @endif
            </flux:navbar.item>
        </div>

        <flux:menu class="w-80">
            <div class="p-2">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Meu Carrinho</h3>
                </div>

                <!-- Removida informação de debug -->

                @if($cart->items->isEmpty())
                    <div class="py-4 text-center">
                        <flux:icon name="shopping-cart" class="mx-auto h-8 w-8 text-gray-400" />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Seu carrinho está vazio</p>
                    </div>
                @else
                    <div class="max-h-60 overflow-y-auto divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($cart->items as $item)
                            <div class="py-2 flex items-center gap-2">
                                <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
                                    <img
                                        src="{{ $item->product->getImageUrl() ?? 'https://placehold.co/600x600/e2e8f0/1e293b?text=Sem+Imagem' }}"
                                        alt="{{ $item->product->name }}"
                                        class="h-full w-full object-cover object-center"
                                    >
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $item->product->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $item->quantity }} x R$ {{ number_format($item->price, 2, ',', '.') }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                                    </p>
                                    <button
                                        wire:click="removeItem({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="removeItem({{ $item->id }})"
                                        class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400"
                                    >
                                        Remover
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Subtotal</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                R$ {{ number_format($cart->total, 2, ',', '.') }}
                            </span>
                        </div>

                        @if($cart->discount > 0)
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Desconto</span>
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                    - R$ {{ number_format($cart->discount, 2, ',', '.') }}
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-between mb-4">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Total</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                R$ {{ number_format($cart->getTotalWithDiscount(), 2, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex space-x-2">
                            <flux:button :href="route('shop.cart')" variant="outline" class="flex-1" size="sm">
                                Ver Carrinho
                            </flux:button>
                            <flux:button :href="route('shop.checkout')" variant="primary" class="flex-1" size="sm">
                                Finalizar
                            </flux:button>
                        </div>
                    </div>
                @endif
            </div>
        </flux:menu>
    </flux:dropdown>
</div>
