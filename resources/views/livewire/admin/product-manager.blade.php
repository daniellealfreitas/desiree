<div>
    <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Gerenciamento de Produtos</h2>
            <flux:button wire:click="create">
                <x-flux::icon name="plus" class="h-4 w-4 mr-2" />
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
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                            Nome
                            @if($sortBy === 'name')
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('price')">
                            Preço
                            @if($sortBy === 'price')
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('stock')">
                            Estoque
                            @if($sortBy === 'stock')
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">
                            Status
                            @if($sortBy === 'status')
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
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

                                @if($product->is_digital)
                                    <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        Digital
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $product->category?->name ?? 'Sem categoria' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <flux:button wire:click="edit({{ $product->id }})" variant="outline" size="xs">
                                    <x-flux::icon name="pencil-square" class="h-4 w-4" />
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $product->id }})" variant="outline" size="xs" class="ml-2">
                                    <x-flux::icon name="trash" class="h-4 w-4 text-red-500" />
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
        <form wire:submit="save" class="space-y-4">
            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-200 p-4 rounded-md mb-4">
                    <p class="font-medium">Corrija os erros abaixo para continuar:</p>
                </div>
            @endif
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <flux:input
                        wire:model="name"
                        label="Nome do Produto"
                        placeholder="Nome do produto"
                        required
                        :error="$errors->first('name')"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <flux:textarea
                        wire:model="description"
                        label="Descrição"
                        placeholder="Descrição do produto"
                        rows="3"
                        :error="$errors->first('description')"
                    />
                </div>

                <div>
                    <flux:input
                        wire:model.defer="price"
                        label="Preço"
                        type="number"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        required
                        :error="$errors->first('price')"
                        help="Use ponto como separador decimal (ex: 10.99)"
                    />
                    @error('price')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model.defer="salePrice"
                        label="Preço Promocional"
                        type="number"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        :error="$errors->first('salePrice')"
                        help="Use ponto como separador decimal (ex: 9.99)"
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
                        :error="$errors->first('stock')"
                    />
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:select
                        wire:model="categoryId"
                        label="Categoria"
                        :error="$errors->first('categoryId')"
                    >
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:select
                        wire:model="status"
                        label="Status"
                        :error="$errors->first('status')"
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
                        :error="$errors->first('saleStartsAt')"
                    />
                </div>

                <div>
                    <flux:input
                        wire:model="saleEndsAt"
                        label="Fim da Promoção"
                        type="date"
                        :error="$errors->first('saleEndsAt')"
                    />
                </div>

                <div class="sm:col-span-2">
                    <flux:checkbox
                        wire:model="featured"
                        label="Produto em Destaque"
                        :error="$errors->first('featured')"
                    />
                </div>

                <!-- Seção de Produto Digital -->
                <div class="sm:col-span-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Configurações de Produto Digital</h3>

                    <div class="mb-4">
                        <flux:checkbox
                            wire:model.live="isDigital"
                            label="Este é um produto digital"
                            :error="$errors->first('isDigital')"
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Produtos digitais não requerem envio físico e podem ser baixados pelo cliente após a compra.
                        </p>
                    </div>

                    @if($isDigital)
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-4">
                            <div class="sm:col-span-2">
                                <x-file-upload
                                    wire:model="digitalFile"
                                    label="Arquivo Digital"
                                    accept=".pdf,.zip,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.mp3,.mp4,.jpg,.jpeg,.png,.gif"
                                    icon="document"
                                    :iconVariant="$digitalFile ? 'solid' : 'outline'"
                                    :required="!$isEditing"
                                    help="Formatos aceitos: PDF, ZIP, DOC, DOCX, XLS, XLSX, PPT, PPTX, MP3, MP4, JPG, JPEG, PNG, GIF (máx. 50MB)"
                                    :error="$errors->first('digitalFile')"
                                />
                                @error('digitalFile')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror

                                @if($isEditing && !$digitalFile && $productId)
                                    @php
                                        $product = App\Models\Product::find($productId);
                                    @endphp
                                    @if($product && $product->digital_file)
                                        <div class="mt-2 flex items-center">
                                            <x-flux::icon name="document" class="h-5 w-5 text-blue-500 mr-2" />
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ $product->digital_file_name ?: 'Arquivo digital' }}
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <div class="sm:col-span-2">
                                <flux:input
                                    wire:model="digitalFileName"
                                    label="Nome do Arquivo para Exibição"
                                    placeholder="Ex: Manual do Produto.pdf"
                                    help="Nome que será exibido para o cliente. Se não informado, será usado o nome original do arquivo."
                                    :error="$errors->first('digitalFileName')"
                                />
                            </div>

                            <div>
                                <flux:input
                                    wire:model="downloadLimit"
                                    label="Limite de Downloads"
                                    type="number"
                                    min="0"
                                    placeholder="Ilimitado"
                                    help="Deixe em branco para downloads ilimitados"
                                    :error="$errors->first('downloadLimit')"
                                />
                            </div>

                            <div>
                                <flux:input
                                    wire:model="downloadExpiryDays"
                                    label="Prazo de Expiração (dias)"
                                    type="number"
                                    min="0"
                                    placeholder="Sem expiração"
                                    help="Número de dias após a compra em que o download estará disponível. Deixe em branco para não expirar."
                                    :error="$errors->first('downloadExpiryDays')"
                                />
                            </div>
                        </div>
                    @endif
                </div>

                <div class="sm:col-span-2">
                    <x-file-upload
                        wire:model="image"
                        label="Imagem Principal"
                        accept="image/*"
                        icon="photo"
                        :iconVariant="$image ? 'solid' : 'outline'"
                        help="Imagem principal do produto (máx. 2MB)"
                        :error="$errors->first('image')"
                    />
                    @error('image')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    @if($image)
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-20 w-20 object-cover rounded">
                        </div>
                    @elseif($isEditing && $productId)
                        @php
                            $product = App\Models\Product::find($productId);
                        @endphp
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
                        help="Imagens adicionais do produto (máx. 2MB cada)"
                        :error="$errors->first('additionalImages.*')"
                    />
                    @error('additionalImages.*')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    @if($additionalImages && count($additionalImages) > 0)
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($additionalImages as $img)
                                <img src="{{ $img->temporaryUrl() }}" alt="Preview" class="h-20 w-20 object-cover rounded">
                            @endforeach
                        </div>
                    @elseif($isEditing && $productId)
                        @php
                            $product = App\Models\Product::find($productId);
                        @endphp
                        @if($product && $product->images && $product->images->count() > 0)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($product->images as $img)
                                    <img src="{{ $img->url }}" alt="{{ $product->name }}" class="h-20 w-20 object-cover rounded">
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
                <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="save" id="submit-product-form">
                    <span wire:loading.remove wire:target="save">
                        {{ $isEditing ? 'Atualizar' : 'Salvar' }}
                    </span>
                    <span wire:loading wire:target="save">
                        <x-flux::icon name="arrow-path" class="h-4 w-4 animate-spin mr-1" />
                        Processando...
                    </span>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Adicionar evento de clique ao botão de envio
        document.getElementById('submit-product-form')?.addEventListener('click', function(e) {
            console.log('Botão de envio clicado');
        });

        // Adicionar evento de envio ao formulário
        const form = document.querySelector('form[wire\\:submit="save"]');
        if (form) {
            console.log('Formulário encontrado');
            form.addEventListener('submit', function(e) {
                console.log('Formulário enviado');
                // Verificar se o formulário está sendo enviado corretamente
                const formData = new FormData(form);
                console.log('Dados do formulário:', {
                    name: formData.get('name'),
                    price: formData.get('price'),
                    stock: formData.get('stock')
                });
            });
        } else {
            console.log('Formulário não encontrado');
        }
    });
</script>