<div>
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 sm:py-12 lg:max-w-7xl lg:px-8">
            <!-- Filtros e Busca -->
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-4">
                <!-- Busca -->
                <div class="md:col-span-4">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Busca</label>
                    <flux:input id="search" wire:model.live.debounce.300ms="search" placeholder="Buscar produtos..." icon="magnifying-glass" />
                </div>

                <!-- Filtros -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Filtros</h3>

                    <!-- Categorias -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categorias</h4>
                        <flux:radio.group wire:model.live="categoryId" variant="segmented" class="flex flex-col space-y-2">
                            <flux:radio value="">Todas as categorias</flux:radio>
                            @foreach($categories as $category)
                                <flux:radio value="{{ $category->id }}">{{ $category->name }}</flux:radio>
                            @endforeach
                        </flux:radio.group>
                    </div>

                    <!-- Faixa de Preço -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Faixa de Preço</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="priceMin" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Preço Mínimo</label>
                                <flux:input id="priceMin" wire:model.live.debounce.500ms="priceMin" type="number" placeholder="Mín" min="0" step="0.01" />
                            </div>
                            <div>
                                <label for="priceMax" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Preço Máximo</label>
                                <flux:input id="priceMax" wire:model.live.debounce.500ms="priceMax" type="number" placeholder="Máx" min="0" step="0.01" />
                            </div>
                        </div>
                    </div>

                    <!-- Filtros adicionais -->
                    <div class="space-y-2">
                        <flux:checkbox.group label="Filtros de produtos">
                            <flux:checkbox wire:model.live="showOnlyOnSale" label="Produtos em promoção" />
                            <flux:checkbox wire:model.live="showOnlyDigital" label="Produtos digitais" />
                            <flux:checkbox wire:model.live="showOnlyPhysical" label="Produtos físicos" />
                            <flux:checkbox wire:model.live="showOnlyAvailable" label="Produtos disponíveis" />
                        </flux:checkbox.group>
                    </div>

                    <!-- Ordenação -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ordenar por</h4>
                        <label for="sortBy" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Campo</label>
                        <flux:select id="sortBy" wire:model.live="sortBy" class="w-full">
                            <option value="name">Nome</option>
                            <option value="price">Preço</option>
                            <option value="created_at">Mais recentes</option>
                        </flux:select>

                        <div class="mt-3">
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Direção</label>
                            <flux:radio.group wire:model.live="sortDirection" variant="segmented">
                                <flux:radio value="asc">Crescente</flux:radio>
                                <flux:radio value="desc">Decrescente</flux:radio>
                            </flux:radio.group>
                        </div>
                    </div>
                </div>

                <!-- Lista de Produtos -->
                <div class="md:col-span-3">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mb-6">Produtos</h2>

                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if ($products->isEmpty())
                        <div class="text-center py-12">
                            <flux:icon name="shopping-bag" class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Nenhum produto encontrado</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tente ajustar seus filtros ou buscar por outro termo.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($products as $product)
                                <div class="group relative">
                                    <div class="aspect-square w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:h-80">
                                        <img
                                            src="{{ $product->getImageUrl() ?? 'https://placehold.co/600x600/e2e8f0/1e293b?text=Sem+Imagem' }}"
                                            alt="{{ $product->name }}"
                                            class="h-full w-full object-cover object-center"
                                        >
                                    </div>

                                    <div class="absolute top-2 right-2 flex flex-col gap-1">
                                        @if ($product->isOnSale())
                                            <div class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                                OFERTA
                                            </div>
                                        @endif

                                        @if ($product->is_digital)
                                            <div class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded flex items-center">
                                                <flux:icon name="document" class="h-3 w-3 mr-1" />
                                                DIGITAL
                                            </div>
                                        @endif

                                        @if ($product->isUnavailable())
                                            <div class="bg-gray-700 text-white text-xs font-bold px-2 py-1 rounded flex items-center">
                                                <flux:icon name="x-mark" class="h-3 w-3 mr-1" />
                                                INDISPONÍVEL
                                            </div>
                                        @endif
                                    </div>

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
                                            @if ($product->isOnSale())
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

                                    <div class="mt-2">
                                        @if($product->isAvailable())
                                            <flux:button
                                                wire:click="addToCart({{ $product->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="addToCart({{ $product->id }})"
                                                variant="primary"
                                                size="sm"
                                                class="w-full"
                                            >
                                                <flux:icon name="shopping-cart" class="h-4 w-4 mr-1" />
                                                Adicionar ao Carrinho
                                            </flux:button>
                                        @else
                                            <flux:button
                                                disabled
                                                variant="outline"
                                                size="sm"
                                                class="w-full opacity-70 cursor-not-allowed"
                                            >
                                                <flux:icon name="x-mark" class="h-4 w-4 mr-1" />
                                                Indisponível
                                            </flux:button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
