<div>
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <flux:icon name="home" class="w-4 h-4 mr-2" />
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <flux:icon name="chevron-right" class="w-4 h-4 text-gray-400" />
                            <a href="{{ route('shop.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Loja</a>
                        </div>
                    </li>
                    @if($product->category)
                    <li>
                        <div class="flex items-center">
                            <flux:icon name="chevron-right" class="w-4 h-4 text-gray-400" />
                            <a href="{{ route('shop.category', $product->category->slug) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{ $product->category->name }}</a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <flux:icon name="chevron-right" class="w-4 h-4 text-gray-400" />
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Produto -->
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <!-- Imagens do Produto -->
                <div class="space-y-4">
                    <div class="aspect-square overflow-hidden rounded-lg bg-gray-200">
                        <img
                            src="{{ $product->getImageUrl() ?? 'https://placehold.co/600x600/e2e8f0/1e293b?text=Sem+Imagem' }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-cover object-center"
                        >
                    </div>

                    @if($product->images->count() > 0)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $image)
                        <div class="aspect-square overflow-hidden rounded-lg bg-gray-200">
                            <img
                                src="{{ $image->url }}"
                                alt="{{ $product->name }}"
                                class="h-full w-full object-cover object-center cursor-pointer hover:opacity-75"
                            >
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Detalhes do Produto -->
                <div class="space-y-6">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">{{ $product->name }}</h1>
                        <div class="flex items-center mt-1">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->category?->name ?? 'Sem categoria' }}</p>

                            @if($product->is_digital)
                                <span class="ml-2 inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-100">
                                    <flux:icon name="document" class="h-3 w-3 mr-1" />
                                    PRODUTO DIGITAL
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center">
                        @if($product->isOnSale())
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                R$ {{ number_format($product->sale_price, 2, ',', '.') }}
                            </p>
                            <p class="ml-3 text-lg text-gray-500 dark:text-gray-400 line-through">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </p>
                            <span class="ml-3 inline-flex items-center rounded-md bg-red-50 dark:bg-red-900 px-2 py-1 text-xs font-medium text-red-700 dark:text-red-100">
                                OFERTA
                            </span>
                        @else
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </p>
                        @endif

                        @if($product->isUnavailable())
                            <span class="ml-3 inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-800 px-2 py-1 text-xs font-medium text-gray-700 dark:text-gray-300">
                                <flux:icon name="x-mark" class="h-3 w-3 mr-1" />
                                INDISPONÍVEL
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Descrição</h3>
                        <div class="mt-2 space-y-4 text-sm text-gray-700 dark:text-gray-300">
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>

                    @if($product->options)
                        @foreach($product->options as $option => $values)
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($option) }}</h3>
                                <div class="mt-2">
                                    <flux:radio.group wire:model.live="selectedOptions.{{ $option }}" variant="segmented">
                                        @foreach($values as $value)
                                            <flux:radio value="{{ $value }}">{{ $value }}</flux:radio>
                                        @endforeach
                                    </flux:radio.group>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Quantidade</h3>
                        <div class="mt-2 flex items-center">
                            @if($product->isAvailable())
                                <flux:button wire:click="decrementQuantity" variant="outline" size="sm" :disabled="$quantity <= 1">
                                    <flux:icon name="minus" class="h-4 w-4" />
                                </flux:button>
                                <span class="mx-4 text-gray-900 dark:text-white">{{ $quantity }}</span>
                                <flux:button wire:click="incrementQuantity" variant="outline" size="sm" :disabled="$quantity >= $product->stock">
                                    <flux:icon name="plus" class="h-4 w-4" />
                                </flux:button>

                                <span class="ml-4 text-sm {{ $product->stock < 10 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $product->stock }} {{ $product->stock == 1 ? 'disponível' : 'disponíveis' }}
                                </span>
                            @else
                                <span class="text-sm text-red-600 dark:text-red-400 font-medium">
                                    Produto indisponível no momento
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        @if($product->isAvailable())
                            <flux:button
                                wire:click="addToCart"
                                wire:loading.attr="disabled"
                                wire:target="addToCart"
                                class="flex-1"
                                id="add-to-cart-button"
                            >
                                <flux:icon name="shopping-cart" class="h-5 w-5 mr-2" />
                                Adicionar ao Carrinho
                            </flux:button>
                        @else
                            <flux:button
                                disabled
                                class="flex-1 opacity-70 cursor-not-allowed"
                                variant="primary"
                            >
                                <flux:icon name="x-mark" class="h-5 w-5 mr-2" />
                                Indisponível
                            </flux:button>
                        @endif

                        <flux:button
                            wire:click="addToWishlist"
                            wire:loading.attr="disabled"
                            wire:target="addToWishlist"
                            variant="outline"
                        >
                            @if($isInWishlist)
                                <flux:icon name="heart" variant="solid" class="h-5 w-5" />
                            @else
                                <flux:icon name="heart" class="h-5 w-5" />
                            @endif
                        </flux:button>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        @if(!$product->is_digital)
                            <div class="flex items-center">
                                <flux:icon name="truck" class="h-5 w-5 text-gray-400 mr-2" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">Retirada no local</span>
                            </div>
                        @else
                            <div class="flex items-center">
                                <flux:icon name="arrow-down-tray" class="h-5 w-5 text-gray-400 mr-2" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">Download imediato após a compra</span>
                            </div>
                        @endif

                        

                        @if($product->is_digital)
                            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-md">
                                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 flex items-center">
                                    <flux:icon name="information-circle" class="h-5 w-5 mr-1" />
                                    Informações sobre o produto digital
                                </h4>
                                <ul class="mt-2 text-xs text-blue-700 dark:text-blue-300 space-y-1 pl-6 list-disc">
                                    <li>Acesso imediato após a confirmação do pagamento</li>
                                    @if($product->download_limit)
                                        <li>Limite de {{ $product->download_limit }} download(s)</li>
                                    @else
                                        <li>Downloads ilimitados</li>
                                    @endif

                                    @if($product->download_expiry_days)
                                        <li>Disponível por {{ $product->download_expiry_days }} dias após a compra</li>
                                    @else
                                        <li>Sem prazo de expiração</li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Produtos Relacionados -->
            @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">Produtos Relacionados</h2>

                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($relatedProducts as $relatedProduct)
                    <div class="group relative">
                        <div class="aspect-square w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:h-80">
                            <img
                                src="{{ $relatedProduct->getImageUrl() ?? 'https://placehold.co/600x600/e2e8f0/1e293b?text=Sem+Imagem' }}"
                                alt="{{ $relatedProduct->name }}"
                                class="h-full w-full object-cover object-center"
                            >
                        </div>

                        @if($relatedProduct->isOnSale())
                            <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                OFERTA
                            </div>
                        @endif

                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700 dark:text-gray-300">
                                    <a href="{{ route('shop.product', $relatedProduct->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h3>
                            </div>
                            <div>
                                @if($relatedProduct->isOnSale())
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        R$ {{ number_format($relatedProduct->sale_price, 2, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-through">
                                        R$ {{ number_format($relatedProduct->price, 2, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        R$ {{ number_format($relatedProduct->price, 2, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
