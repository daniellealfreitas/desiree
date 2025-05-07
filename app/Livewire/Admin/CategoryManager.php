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

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'parentId' => 'nullable|exists:categories,id',
        'isActive' => 'boolean',
        'image' => 'nullable|image|max:2048',
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
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
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
        $this->validate();
        
        try {
            if ($this->isEditing) {
                $category = Category::findOrFail($this->categoryId);
            } else {
                $category = new Category();
                $category->slug = Str::slug($this->name);
            }
            
            $category->name = $this->name;
            $category->description = $this->description;
            $category->parent_id = $this->parentId;
            $category->is_active = $this->isActive;
            
            // Upload da imagem
            if ($this->image) {
                $imagePath = $this->image->store('categories', 'public');
                $category->image = Storage::url($imagePath);
            }
            
            $category->save();
            
            $this->showModal = false;
            $this->resetForm();
            
            $this->dispatch('notify', [
                'message' => $this->isEditing ? 'Categoria atualizada com sucesso!' : 'Categoria criada com sucesso!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao salvar categoria: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->deleteId = $id;
    }

    public function delete()
    {
        try {
            $category = Category::findOrFail($this->deleteId);
            
            // Verificar se há produtos associados
            if ($category->products()->count() > 0) {
                throw new \Exception('Não é possível excluir uma categoria com produtos associados.');
            }
            
            // Verificar se há subcategorias
            if ($category->children()->count() > 0) {
                throw new \Exception('Não é possível excluir uma categoria com subcategorias.');
            }
            
            // Remover imagem
            if ($category->image) {
                $path = str_replace('/storage', 'public', $category->image);
                Storage::delete($path);
            }
            
            $category->delete();
            
            $this->confirmingDelete = false;
            $this->deleteId = null;
            
            $this->dispatch('notify', [
                'message' => 'Categoria excluída com sucesso!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
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
        
        $this->resetErrorBag();
    }

    public function render()
    {
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
