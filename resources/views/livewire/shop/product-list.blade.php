<div>
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 sm:py-12 lg:max-w-7xl lg:px-8">
            <!-- Filtros e Busca -->
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-4">
                <!-- Busca -->
                <div class="md:col-span-4">
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar produtos..." icon="magnifying-glass" />
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
                            <flux:input wire:model.live.debounce.500ms="priceMin" type="number" placeholder="Mín" min="0" step="0.01" />
                            <flux:input wire:model.live.debounce.500ms="priceMax" type="number" placeholder="Máx" min="0" step="0.01" />
                        </div>
                    </div>
                    
                    <!-- Apenas Promoções -->
                    <div>
                        <flux:checkbox wire:model.live="showOnlyOnSale">
                            Apenas produtos em promoção
                        </flux:checkbox>
                    </div>
                    
                    <!-- Ordenação -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ordenar por</h4>
                        <flux:select wire:model.live="sortBy" class="w-full">
                            <option value="name">Nome</option>
                            <option value="price">Preço</option>
                            <option value="created_at">Mais recentes</option>
                        </flux:select>
                        
                        <div class="mt-2">
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
                                    
                                    @if ($product->isOnSale())
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
                                        <flux:button 
                                            wire:click="addToCart({{ $product->id }})" 
                                            wire:loading.attr="disabled"
                                            wire:target="addToCart({{ $product->id }})"
                                            variant="secondary" 
                                            size="sm" 
                                            class="w-full"
                                        >
                                            <flux:icon name="shopping-cart" class="h-4 w-4 mr-1" />
                                            Adicionar ao Carrinho
                                        </flux:button>
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
