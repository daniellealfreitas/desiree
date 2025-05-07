<div>
    <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Gerenciamento de Produtos</h2>
            <flux:button wire:click="create">
                <flux:icon name="plus" class="h-4 w-4 mr-2" />
                Novo Produto
            </flux:button>
        </div>

        <!-- Filtros e Busca -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar produtos..."
                icon="magnifying-glass"
            />

            <flux:select wire:model.live="categoryFilter">
                <option value="">Todas as categorias</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="perPage">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </flux:select>
        </div>

        <!-- Tabela de Produtos -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('id')">
                            ID
                            @if($sortBy === 'id')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                            Nome
                            @if($sortBy === 'name')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('price')">
                            Preço
                            @if($sortBy === 'price')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('stock')">
                            Estoque
                            @if($sortBy === 'stock')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">
                            Status
                            @if($sortBy === 'status')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Categoria
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $product->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img
                                            src="{{ $product->getImageUrl() ?? 'https://placehold.co/100x100/e2e8f0/1e293b?text=Sem+Imagem' }}"
                                            alt="{{ $product->name }}"
                                            class="h-10 w-10 rounded-full object-cover"
                                        >
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $product->name }}
                                        </div>
                                        @if($product->sku)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                SKU: {{ $product->sku }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($product->isOnSale())
                                    <span class="text-green-600 dark:text-green-400 font-medium">
                                        R$ {{ number_format($product->sale_price, 2, ',', '.') }}
                                    </span>
                                    <span class="text-gray-400 dark:text-gray-500 line-through text-xs ml-1">
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    </span>
                                @else
                                    <span>
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($product->stock > 10)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        {{ $product->stock }} unidades
                                    </span>
                                @elseif($product->stock > 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                        {{ $product->stock }} unidades
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                        Esgotado
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($product->status === 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        Ativo
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                                        Inativo
                                    </span>
                                @endif

                                @if($product->featured)
                                    <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                        Destaque
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $product->category?->name ?? 'Sem categoria' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <flux:button wire:click="edit({{ $product->id }})" variant="outline" size="xs">
                                    <flux:icon name="pencil-square" class="h-4 w-4" />
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $product->id }})" variant="outline" size="xs" class="ml-2">
                                    <flux:icon name="trash" class="h-4 w-4 text-red-500" />
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Nenhum produto encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Modal de Formulário -->
    <flux:modal wire:model="showModal" title="{{ $isEditing ? 'Editar Produto' : 'Novo Produto' }}">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <flux:input
                        wire:model="name"
                        label="Nome do Produto"
                        placeholder="Nome do produto"
                        required
                    />
                </div>

                <div class="sm:col-span-2">
                    <flux:textarea
                        wire:model="description"
                        label="Descrição"
                        placeholder="Descrição do produto"
                        rows="3"
                    />
                </div>

                <div>
                    <flux:input
                        wire:model="price"
                        label="Preço"
                        type="number"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        required
                    />
                </div>

                <div>
                    <flux:input
                        wire:model="salePrice"
                        label="Preço Promocional"
                        type="number"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                    />
                </div>

                <div>
                    <flux:input
                        wire:model="stock"
                        label="Estoque"
                        type="number"
                        min="0"
                        placeholder="0"
                        required
                    />
                </div>

                <div>
                    <flux:select
                        wire:model="categoryId"
                        label="Categoria"
                    >
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:input
                        wire:model="sku"
                        label="SKU"
                        placeholder="Código do produto"
                    />
                </div>

                <div>
                    <flux:input
                        wire:model="color"
                        label="Cor"
                        placeholder="Cor do produto"
                    />
                </div>

                <div>
                    <flux:input
                        wire:model="weight"
                        label="Peso (kg)"
                        type="number"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                    />
                </div>

                <div>
                    <flux:select
                        wire:model="status"
                        label="Status"
                    >
                        <option value="active">Ativo</option>
                        <option value="inactive">Inativo</option>
                    </flux:select>
                </div>

                <div>
                    <flux:input
                        wire:model="saleStartsAt"
                        label="Início da Promoção"
                        type="date"
                    />
                </div>

                <div>
                    <flux:input
                        wire:model="saleEndsAt"
                        label="Fim da Promoção"
                        type="date"
                    />
                </div>

                <div class="sm:col-span-2">
                    <flux:checkbox
                        wire:model="featured"
                        label="Produto em Destaque"
                    />
                </div>

                <div class="sm:col-span-2">
                    <x-file-upload
                        wire:model="image"
                        label="Imagem Principal"
                        accept="image/*"
                        icon="photo"
                        :iconVariant="$image ? 'solid' : 'outline'"
                    />

                    @if($image)
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-20 w-20 object-cover rounded">
                        </div>
                    @elseif($isEditing && $productId)
                        @php $product = App\Models\Product::find($productId); @endphp
                        @if($product && $product->image)
                            <div class="mt-2">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-20 w-20 object-cover rounded">
                            </div>
                        @endif
                    @endif
                </div>

                <div class="sm:col-span-2">
                    <x-file-upload
                        wire:model="additionalImages"
                        label="Imagens Adicionais"
                        accept="image/*"
                        multiple
                        icon="photo"
                    />

                    @if($additionalImages)
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($additionalImages as $image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-20 w-20 object-cover rounded">
                            @endforeach
                        </div>
                    @elseif($isEditing && $productId)
                        @php $product = App\Models\Product::find($productId); @endphp
                        @if($product && $product->images->count() > 0)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($product->images as $image)
                                    <img src="{{ $image->url }}" alt="{{ $product->name }}" class="h-20 w-20 object-cover rounded">
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <flux:button type="button" variant="outline" wire:click="$set('showModal', false)">
                    Cancelar
                </flux:button>
                <flux:button type="submit">
                    {{ $isEditing ? 'Atualizar' : 'Salvar' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Modal de Confirmação de Exclusão -->
    <flux:modal wire:model="confirmingDelete" title="Confirmar Exclusão">
        <div class="p-4">
            <p class="text-gray-700 dark:text-gray-300">
                Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.
            </p>

            <div class="mt-6 flex justify-end space-x-3">
                <flux:button variant="outline" wire:click="$set('confirmingDelete', false)">
                    Cancelar
                </flux:button>
                <flux:button variant="danger" wire:click="delete">
                    Excluir
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
