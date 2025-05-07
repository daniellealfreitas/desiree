<div>
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Lista de Desejos</h1>

            <!-- Lista de Produtos -->
            <div class="mt-8">
                @if($wishlist->isEmpty())
                    <div class="text-center py-12 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                        <flux:icon name="heart" class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Sua lista de desejos está vazia</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Adicione produtos à sua lista de desejos para encontrá-los facilmente mais tarde.</p>
                        <div class="mt-6">
                            <flux:button :href="route('shop.index')" variant="primary">
                                Explorar Produtos
                            </flux:button>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                        @foreach($wishlist as $product)
                            <div class="group relative">
                                <div class="aspect-square w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:h-80">
                                    <img
                                        src="{{ $product->getImageUrl() ?? 'https://placehold.co/600x600/e2e8f0/1e293b?text=Sem+Imagem' }}"
                                        alt="{{ $product->name }}"
                                        class="h-full w-full object-cover object-center"
                                    >
                                </div>

                                @if($product->isOnSale())
                                    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                        OFERTA
                                    </div>
                                @endif

                                <div class="mt-4 flex justify-between">
                                    <div>
                                        <h3 class="text-sm text-gray-700 dark:text-gray-300">
                                            <a href="{{ route('shop.product', $product->slug) }}">
                                                <span aria-hidden="true" class="absolute inset-0"></span>
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->category?->name ?? 'Sem categoria' }}
                                        </p>
                                    </div>
                                    <div>
                                        @if($product->isOnSale())
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                R$ {{ number_format($product->sale_price, 2, ',', '.') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 line-through">
                                                R$ {{ number_format($product->price, 2, ',', '.') }}
                                            </p>
                                        @else
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                R$ {{ number_format($product->price, 2, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-2 flex space-x-2">
                                    <flux:button
                                        wire:click="addToCart({{ $product->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="addToCart({{ $product->id }})"
                                        variant="outline"
                                        size="sm"
                                        class="flex-1"
                                    >
                                        <flux:icon name="shopping-cart" class="h-4 w-4 mr-1" />
                                        Adicionar
                                    </flux:button>

                                    <flux:button
                                        wire:click="removeFromWishlist({{ $product->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="removeFromWishlist({{ $product->id }})"
                                        variant="outline"
                                        size="sm"
                                    >
                                        <flux:icon name="trash" class="h-4 w-4 text-red-500" />
                                    </flux:button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $wishlist->links() }}
                    </div>
                @endif
            </div>

            <div class="mt-8 flex justify-center">
                <flux:button :href="route('shop.index')" variant="outline">
                    <flux:icon name="arrow-left" class="h-4 w-4 mr-2" />
                    Voltar para a Loja
                </flux:button>
            </div>
        </div>
    </div>
</div>
