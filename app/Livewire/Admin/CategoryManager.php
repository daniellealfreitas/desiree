<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Campos do formulário
    public $categoryId;
    public $name;
    public $description;
    public $parentId;
    public $isActive = true;
    public $image;

    // Controle de modal
    public $showModal = false;
    public $confirmingDelete = false;
    public $deleteId;
    public $isEditing = false;
    public $detachRelatedProducts = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'parentId' => 'nullable|exists:categories,id',
        'isActive' => 'boolean',
        'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
    ];

    protected $messages = [
        'name.required' => 'O nome da categoria é obrigatório.',
        'name.max' => 'O nome da categoria não pode ter mais de 255 caracteres.',
        'parentId.exists' => 'A categoria pai selecionada não existe.',
        'image.image' => 'O arquivo deve ser uma imagem válida.',
        'image.max' => 'A imagem não pode ter mais de 2MB.',
        'image.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou gif.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        logger()->info('CategoryManager: create() method called');
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
        logger()->info('CategoryManager: Modal should be open now', ['showModal' => $this->showModal]);
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->categoryId = $id;

        $category = Category::findOrFail($id);

        $this->name = $category->name;
        $this->description = $category->description;
        $this->parentId = $category->parent_id;
        $this->isActive = $category->is_active;

        $this->showModal = true;
    }

    public function save()
    {
        logger()->info('CategoryManager: save() method called');

        // Validar os dados do formulário
        $this->validate();

        try {
            // Verificar se já existe uma categoria com o mesmo nome
            $existingCategory = Category::where('name', $this->name)
                ->when($this->isEditing, function ($query) {
                    return $query->where('id', '!=', $this->categoryId);
                })
                ->first();

            if ($existingCategory) {
                $this->addError('name', 'Já existe uma categoria com este nome. Por favor, escolha outro nome.');
                return;
            }

            // Verificar se está editando ou criando uma nova categoria
            if ($this->isEditing) {
                $category = Category::findOrFail($this->categoryId);
            } else {
                $category = new Category();
                $category->slug = Str::slug($this->name);
            }

            // Atribuir os valores dos campos do formulário
            $category->name = $this->name;
            $category->description = $this->description;
            $category->parent_id = $this->parentId;
            $category->is_active = $this->isActive;

            // Verificar se a categoria pai não é a própria categoria
            if ($this->isEditing && $this->parentId == $this->categoryId) {
                $this->addError('parentId', 'Uma categoria não pode ser sua própria categoria pai.');
                return;
            }

            // Verificar se a categoria pai não é uma subcategoria da categoria atual (para evitar loops)
            if ($this->isEditing && $this->parentId) {
                $parentCategory = Category::find($this->parentId);
                $currentParentId = $parentCategory->parent_id;

                while ($currentParentId) {
                    if ($currentParentId == $this->categoryId) {
                        $this->addError('parentId', 'Não é possível selecionar uma subcategoria como categoria pai (isso criaria um loop).');
                        return;
                    }
                    $parent = Category::find($currentParentId);
                    $currentParentId = $parent ? $parent->parent_id : null;
                }
            }

            // Salvar a categoria primeiro para garantir que ela tenha um ID
            $category->save();

            // Upload da imagem
            if ($this->image) {
                try {
                    // Verificar se a imagem é um objeto de upload válido
                    if (is_object($this->image) && method_exists($this->image, 'store')) {
                        // Criar diretório se não existir
                        if (!file_exists(storage_path('app/public/categories'))) {
                            mkdir(storage_path('app/public/categories'), 0755, true);
                        }

                        $imagePath = $this->image->store('categories', 'public');
                        $category->image = Storage::url($imagePath);

                        // Salvar novamente para atualizar o caminho da imagem
                        $category->save();
                    } else {
                        logger()->error('Objeto de imagem inválido', [
                            'image_type' => gettype($this->image),
                            'image_class' => is_object($this->image) ? get_class($this->image) : 'N/A'
                        ]);
                        $this->addError('image', 'O arquivo enviado não é uma imagem válida.');
                        return;
                    }
                } catch (\Exception $e) {
                    $this->addError('image', 'Erro ao fazer upload da imagem: ' . $e->getMessage());
                    logger()->error('Erro ao fazer upload da imagem: ' . $e->getMessage(), [
                        'image_type' => gettype($this->image),
                        'image_class' => is_object($this->image) ? get_class($this->image) : 'N/A',
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Não lançar exceção para permitir que a categoria seja salva mesmo sem imagem
                }
            }

            // Se chegou até aqui, tudo ocorreu bem
            $this->showModal = false;
            $this->resetForm();

            $this->dispatch('notify', [
                'message' => $this->isEditing ? 'Categoria atualizada com sucesso!' : 'Categoria criada com sucesso!',
                'type' => 'success'
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Capturar erros específicos de banco de dados
            logger()->error('Erro de banco de dados ao salvar categoria: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Verificar se é um erro de chave duplicada
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                $this->addError('name', 'Já existe uma categoria com este nome. Por favor, escolha outro nome.');
            } else {
                $this->addError('form', 'Erro de banco de dados: ' . $e->getMessage());
            }

            $this->dispatch('notify', [
                'message' => 'Erro ao salvar categoria. Verifique os campos do formulário.',
                'type' => 'error'
            ]);
        } catch (\Exception $e) {
            // Capturar outros erros
            logger()->error('Erro ao salvar categoria: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            $this->addError('form', 'Erro ao salvar categoria: ' . $e->getMessage());

            $this->dispatch('notify', [
                'message' => 'Erro ao salvar categoria: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function confirmDelete($id)
    {
        logger()->info('CategoryManager: confirmDelete() method called', ['id' => $id]);

        // Limpar erros anteriores
        $this->resetErrorBag();

        // Verificar se a categoria existe
        $category = Category::find($id);
        if (!$category) {
            logger()->error('CategoryManager: Categoria não encontrada para confirmação de exclusão', ['id' => $id]);
            $this->dispatch('notify', [
                'message' => 'Categoria não encontrada',
                'type' => 'error'
            ]);
            return;
        }

        // Verificar se a categoria pode ser excluída
        $canDelete = true;
        $errorMessage = '';

        // Verificar produtos associados (one-to-many)
        // Comentado porque a coluna category_id não existe na tabela products
        // $productsCount = $category->products()->count();
        // if ($productsCount > 0) {
        //     $canDelete = false;
        //     $errorMessage = "Esta categoria possui {$productsCount} produto(s) associado(s). Remova os produtos ou altere a categoria deles antes de excluir.";
        //     logger()->warning('CategoryManager: Categoria tem produtos associados (one-to-many)', ['count' => $productsCount]);
        // }

        // Verificar produtos relacionados (many-to-many)
        $relatedProductsCount = $category->relatedProducts()->count();
        if ($relatedProductsCount > 0) {
            $canDelete = false;
            $errorMessage = "Esta categoria está relacionada a {$relatedProductsCount} produto(s) na tabela pivot. Remova estas relações antes de excluir.";
            logger()->warning('CategoryManager: Categoria tem produtos relacionados (many-to-many)', ['count' => $relatedProductsCount]);
        }

        // Verificar subcategorias
        if ($canDelete) {
            $childrenCount = $category->children()->count();
            if ($childrenCount > 0) {
                $canDelete = false;
                $errorMessage = "Esta categoria possui {$childrenCount} subcategoria(s). Remova as subcategorias ou mova-as para outra categoria antes de excluir.";
                logger()->warning('CategoryManager: Categoria tem subcategorias', ['count' => $childrenCount]);
            }
        }

        // Se não puder excluir, mostrar erro
        if (!$canDelete) {
            $this->addError('form', $errorMessage);
        }

        // Definir ID e abrir modal
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        logger()->info('CategoryManager: delete() method called', ['deleteId' => $this->deleteId]);

        try {
            // Verificar se o ID de exclusão está definido
            if (!$this->deleteId) {
                logger()->error('CategoryManager: deleteId não está definido');
                $this->addError('form', 'ID da categoria não fornecido para exclusão.');
                $this->dispatch('notify', [
                    'message' => 'Erro ao excluir categoria: ID não fornecido',
                    'type' => 'error'
                ]);
                return;
            }

            $category = Category::find($this->deleteId);

            // Verificar se a categoria existe
            if (!$category) {
                logger()->error('CategoryManager: Categoria não encontrada', ['deleteId' => $this->deleteId]);
                $this->addError('form', 'Categoria não encontrada.');
                $this->dispatch('notify', [
                    'message' => 'Erro ao excluir categoria: Categoria não encontrada',
                    'type' => 'error'
                ]);
                return;
            }

            logger()->info('CategoryManager: Categoria encontrada', [
                'id' => $category->id,
                'name' => $category->name
            ]);

            // Verificar se há produtos associados (one-to-many)
            // Comentado porque a coluna category_id não existe na tabela products
            // $productsCount = $category->products()->count();
            // logger()->info('CategoryManager: Verificando produtos associados (one-to-many)', ['count' => $productsCount]);
            //
            // if ($productsCount > 0) {
            //     logger()->warning('CategoryManager: Categoria tem produtos associados (one-to-many)', ['count' => $productsCount]);
            //     $this->addError('form', 'Não é possível excluir uma categoria com produtos associados.');
            //     $this->dispatch('notify', [
            //         'message' => 'Não é possível excluir uma categoria com produtos associados.',
            //         'type' => 'error'
            //     ]);
            //     return;
            // }

            // Verificar se há produtos relacionados (many-to-many)
            $relatedProductsCount = $category->relatedProducts()->count();
            logger()->info('CategoryManager: Verificando produtos relacionados (many-to-many)', [
                'count' => $relatedProductsCount,
                'detachRelatedProducts' => $this->detachRelatedProducts
            ]);

            if ($relatedProductsCount > 0) {
                // Se a opção de remover relações estiver marcada
                if ($this->detachRelatedProducts) {
                    logger()->info('CategoryManager: Removendo relações many-to-many', ['count' => $relatedProductsCount]);
                    try {
                        // Remover relações na tabela pivot
                        $category->relatedProducts()->detach();
                        logger()->info('CategoryManager: Relações many-to-many removidas com sucesso');
                    } catch (\Exception $e) {
                        logger()->error('CategoryManager: Erro ao remover relações many-to-many', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        $this->addError('form', 'Erro ao remover relações com produtos: ' . $e->getMessage());
                        $this->dispatch('notify', [
                            'message' => 'Erro ao remover relações com produtos: ' . $e->getMessage(),
                            'type' => 'error'
                        ]);
                        return;
                    }
                } else {
                    // Se a opção não estiver marcada, impedir a exclusão
                    logger()->warning('CategoryManager: Categoria tem produtos relacionados (many-to-many)', ['count' => $relatedProductsCount]);
                    $this->addError('form', 'Esta categoria está relacionada a produtos na tabela pivot. Marque a opção para remover estas relações ou remova-as manualmente antes de excluir.');
                    $this->dispatch('notify', [
                        'message' => 'Esta categoria está relacionada a produtos na tabela pivot. Marque a opção para remover estas relações ou remova-as manualmente antes de excluir.',
                        'type' => 'error'
                    ]);
                    return;
                }
            }

            // Verificar se há subcategorias
            $childrenCount = $category->children()->count();
            logger()->info('CategoryManager: Verificando subcategorias', ['count' => $childrenCount]);

            if ($childrenCount > 0) {
                logger()->warning('CategoryManager: Categoria tem subcategorias', ['count' => $childrenCount]);
                $this->addError('form', 'Não é possível excluir uma categoria com subcategorias.');
                $this->dispatch('notify', [
                    'message' => 'Não é possível excluir uma categoria com subcategorias.',
                    'type' => 'error'
                ]);
                return;
            }

            // Remover imagem
            if ($category->image) {
                logger()->info('CategoryManager: Removendo imagem da categoria', ['image' => $category->image]);
                $path = str_replace('/storage', 'public', $category->image);
                try {
                    Storage::delete($path);
                } catch (\Exception $e) {
                    logger()->warning('CategoryManager: Erro ao excluir imagem', [
                        'error' => $e->getMessage(),
                        'path' => $path
                    ]);
                    // Continuar mesmo se a imagem não puder ser excluída
                }
            }

            // Excluir a categoria
            logger()->info('CategoryManager: Tentando excluir categoria', ['id' => $category->id]);
            $deleted = $category->delete();

            if (!$deleted) {
                logger()->error('CategoryManager: Falha ao excluir categoria', ['id' => $category->id]);
                throw new \Exception('Falha ao excluir a categoria. Tente novamente.');
            }

            logger()->info('CategoryManager: Categoria excluída com sucesso', ['id' => $category->id]);

            // Resetar o estado do modal de confirmação
            $this->resetDeleteConfirmation();

            $this->dispatch('notify', [
                'message' => 'Categoria excluída com sucesso!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            logger()->error('CategoryManager: Erro ao excluir categoria', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->addError('form', 'Erro ao excluir categoria: ' . $e->getMessage());
            $this->dispatch('notify', [
                'message' => 'Erro ao excluir categoria: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function resetForm()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->description = '';
        $this->parentId = null;
        $this->isActive = true;
        $this->image = null;

        $this->resetValidation();
        $this->resetErrorBag();
    }

    /**
     * Resetar o estado do modal de confirmação de exclusão
     */
    public function resetDeleteConfirmation()
    {
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->detachRelatedProducts = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        logger()->debug('CategoryManager: render() method called', [
            'showModal' => $this->showModal,
            'isEditing' => $this->isEditing
        ]);
        $query = Category::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        $categories = $query->paginate($this->perPage);
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('livewire.admin.category-manager', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
        ]);
    }
}
