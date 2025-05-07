<div>
    <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Gerenciamento de Categorias</h2>
            <flux:button wire:click="create">
                <flux:icon name="plus" class="h-4 w-4 mr-2" />
                Nova Categoria
            </flux:button>
        </div>

        <!-- Filtros e Busca -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar categorias..."
                icon="magnifying-glass"
            />

            <flux:select wire:model.live="perPage">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </flux:select>
        </div>

        <!-- Tabela de Categorias -->
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Descrição
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Categoria Pai
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('is_active')">
                            Status
                            @if($sortBy === 'is_active')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $category->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($category->image)
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <img
                                                src="{{ $category->image }}"
                                                alt="{{ $category->name }}"
                                                class="h-10 w-10 rounded-full object-cover"
                                            >
                                        </div>
                                    @else
                                        <div class="h-10 w-10 flex-shrink-0 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                            <flux:icon name="folder" class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $category->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $category->slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <div class="max-w-xs truncate">
                                    {{ $category->description ?? 'Sem descrição' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $category->parent?->name ?? 'Categoria principal' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($category->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        Ativa
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                                        Inativa
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <flux:button wire:click="edit({{ $category->id }})" variant="outline" size="xs">
                                    <flux:icon name="pencil-square" class="h-4 w-4" />
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $category->id }})" variant="outline" size="xs" class="ml-2">
                                    <flux:icon name="trash" class="h-4 w-4 text-red-500" />
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Nenhuma categoria encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Modal de Formulário -->
    <flux:modal wire:model="showModal" title="{{ $isEditing ? 'Editar Categoria' : 'Nova Categoria' }}">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <flux:input
                        wire:model="name"
                        label="Nome da Categoria"
                        placeholder="Nome da categoria"
                        required
                    />
                </div>

                <div>
                    <flux:textarea
                        wire:model="description"
                        label="Descrição"
                        placeholder="Descrição da categoria"
                        rows="3"
                    />
                </div>

                <div>
                    <flux:select
                        wire:model="parentId"
                        label="Categoria Pai"
                    >
                        <option value="">Nenhuma (categoria principal)</option>
                        @foreach($parentCategories as $parent)
                            @if(!$isEditing || ($isEditing && $parent->id != $categoryId))
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endif
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:checkbox
                        wire:model="isActive"
                        label="Categoria Ativa"
                    />
                </div>

                <div>
                    <x-file-upload
                        wire:model="image"
                        label="Imagem da Categoria"
                        accept="image/*"
                        icon="photo"
                        :iconVariant="$image ? 'solid' : 'outline'"
                    />

                    @if($image)
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-20 w-20 object-cover rounded">
                        </div>
                    @elseif($isEditing && $categoryId)
                        @php $category = App\Models\Category::find($categoryId); @endphp
                        @if($category && $category->image)
                            <div class="mt-2">
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="h-20 w-20 object-cover rounded">
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
                Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.
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
