<div
    x-data="{
        init() {
            console.log('CategoryManager component initialized');

            // Listen for showModal changes
            $watch('$wire.showModal', (value) => {
                console.log('showModal changed:', value);
            });
        }
    }"
>
    <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Gerenciamento de Categorias</h2>
            <x-flux::button wire:click.prevent="create" type="button">
                <x-flux::icon name="plus" class="h-4 w-4 mr-2" />Nova Categoria
            </x-flux::button>
        </div>

        <!-- Filtros e Busca -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <x-flux::input
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar categorias..."
                icon="magnifying-glass"
            />

            <x-flux::select wire:model.live="perPage">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </x-flux::select>
        </div>

        <!-- Tabela de Categorias -->
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Descrição
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Categoria Pai
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('is_active')">
                            Status
                            @if($sortBy === 'is_active')
                                <x-flux::icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
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
                                            <x-flux::icon name="folder" class="h-5 w-5 text-gray-500 dark:text-gray-400" />
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
                                <x-flux::button wire:click.prevent="edit({{ $category->id }})" variant="outline" size="xs" type="button">
                                    <x-flux::icon name="pencil-square" class="h-4 w-4" />
                                </x-flux::button>
                                <x-flux::button wire:click.prevent="confirmDelete({{ $category->id }})" variant="outline" size="xs" class="ml-2" type="button">
                                    <x-flux::icon name="trash" class="h-4 w-4 text-red-500" />
                                </x-flux::button>
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
    <x-flux::modal
        wire:model="showModal"
        title="{{ $isEditing ? 'Editar Categoria' : 'Nova Categoria' }}"
        x-on:open="console.log('Modal opened')"
        x-on:close="console.log('Modal closed')"
    >
        <form
            wire:submit.prevent="save"
            class="space-y-4"
            enctype="multipart/form-data"
            x-on:submit="console.log('Form submitted')"
        >
            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Existem erros no formulário. Por favor, corrija-os antes de continuar.
                            </h3>

                            @if($errors->has('form'))
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300 font-medium">
                                    {{ $errors->first('form') }}
                                </div>
                            @endif

                            @if($errors->count() > 3)
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <x-flux::input
                        wire:model.live="name"
                        label="Nome da Categoria *"
                        placeholder="Nome da categoria"
                        required
                        :error="$errors->first('name')"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">O nome da categoria é obrigatório e deve ser único.</p>
                </div>

                <div>
                    <x-flux::textarea
                        wire:model.live="description"
                        label="Descrição"
                        placeholder="Descrição da categoria"
                        rows="3"
                        :error="$errors->first('description')"
                    />
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Uma breve descrição da categoria (opcional).</p>
                </div>

                <div>
                    <x-flux::select
                        wire:model.live="parentId"
                        label="Categoria Pai"
                        :error="$errors->first('parentId')"
                    >
                        <option value="">Nenhuma (categoria principal)</option>
                        @foreach($parentCategories as $parent)
                            @if(!$isEditing || ($isEditing && $parent->id != $categoryId))
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endif
                        @endforeach
                    </x-flux::select>
                    @error('parentId')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Selecione uma categoria pai ou deixe em branco para criar uma categoria principal.</p>
                </div>

                <div>
                    <x-flux::checkbox
                        wire:model.live="isActive"
                        label="Categoria Ativa"
                    />
                    @error('isActive')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Desmarque esta opção para desativar a categoria (ela não aparecerá no site).</p>
                </div>

                <div>
                    <div class="mb-2">
                        <label for="image-upload" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Imagem da Categoria
                        </label>
                        <div class="mt-1 flex items-center">
                            <input
                                type="file"
                                id="image-upload"
                                wire:model="image"
                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                            />
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Formatos aceitos: JPG, JPEG, PNG, GIF (máx. 2MB). A imagem será exibida como ícone da categoria.</p>
                    </div>

                    <div wire:loading wire:target="image" class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="bg-red-600 h-2 rounded-full animate-pulse" style="width: 100%"></div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Carregando imagem...</p>
                    </div>

                    @if($image && method_exists($image, 'temporaryUrl'))
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
                <x-flux::button type="button" variant="outline" wire:click.prevent="resetForm(); $wire.set('showModal', false)">
                    Cancelar
                </x-flux::button>
                <x-flux::button type="submit">
                    {{ $isEditing ? 'Atualizar' : 'Salvar' }}
                </x-flux::button>
            </div>
        </form>
    </x-flux::modal>

    <!-- Modal de Confirmação de Exclusão -->
    <x-flux::modal wire:model="confirmingDelete" title="Confirmar Exclusão">
        <div class="p-4">
            @if($errors->has('form'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                {{ $errors->first('form') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <p class="text-gray-700 dark:text-gray-300">
                Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.
            </p>

            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <p class="mb-1"><strong>Importante:</strong> Antes de excluir, verifique se:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>A categoria não possui subcategorias</li>
                    <li>A categoria não está relacionada a produtos na tabela pivot</li>
                </ul>
            </div>

            <div class="mt-4">
                <x-flux::checkbox
                    wire:model.live="detachRelatedProducts"
                    label="Remover relações com produtos antes de excluir"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Marque esta opção para remover automaticamente as relações many-to-many com produtos antes de excluir a categoria.
                </p>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <x-flux::button variant="outline" wire:click.prevent="resetDeleteConfirmation" type="button">
                    Cancelar
                </x-flux::button>
                <x-flux::button variant="danger" wire:click.prevent="delete" type="button">
                    Excluir
                </x-flux::button>
            </div>
        </div>
    </x-flux::modal>
</div>
