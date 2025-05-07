<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $categoryFilter = '';

    // Campos do formulário
    public $productId;
    public $name;
    public $description;
    public $price;
    public $salePrice;
    public $stock;
    public $categoryId;
    public $featured = false;
    public $status = 'active';
    public $sku;
    public $weight;
    public $color;
    public $saleStartsAt;
    public $saleEndsAt;
    public $image;
    public $additionalImages = [];

    // Controle de modal
    public $showModal = false;
    public $confirmingDelete = false;
    public $deleteId;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'salePrice' => 'nullable|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'categoryId' => 'nullable|exists:categories,id',
        'featured' => 'boolean',
        'status' => 'required|in:active,inactive',
        'sku' => 'nullable|string|max:100',
        'weight' => 'nullable|numeric|min:0',
        'color' => 'nullable|string|max:50',
        'saleStartsAt' => 'nullable|date',
        'saleEndsAt' => 'nullable|date|after_or_equal:saleStartsAt',
        'image' => 'nullable|image|max:2048',
        'additionalImages.*' => 'nullable|image|max:2048',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
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
        $this->productId = $id;
        
        $product = Product::findOrFail($id);
        
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->salePrice = $product->sale_price;
        $this->stock = $product->stock;
        $this->categoryId = $product->category_id;
        $this->featured = $product->featured;
        $this->status = $product->status;
        $this->sku = $product->sku;
        $this->weight = $product->weight;
        $this->color = $product->color;
        $this->saleStartsAt = $product->sale_starts_at ? $product->sale_starts_at->format('Y-m-d') : null;
        $this->saleEndsAt = $product->sale_ends_at ? $product->sale_ends_at->format('Y-m-d') : null;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        
        try {
            if ($this->isEditing) {
                $product = Product::findOrFail($this->productId);
            } else {
                $product = new Product();
                $product->slug = Str::slug($this->name);
            }
            
            $product->name = $this->name;
            $product->description = $this->description;
            $product->price = $this->price;
            $product->sale_price = $this->salePrice;
            $product->stock = $this->stock;
            $product->category_id = $this->categoryId;
            $product->featured = $this->featured;
            $product->status = $this->status;
            $product->sku = $this->sku;
            $product->weight = $this->weight;
            $product->color = $this->color;
            $product->sale_starts_at = $this->saleStartsAt;
            $product->sale_ends_at = $this->saleEndsAt;
            
            // Upload da imagem principal
            if ($this->image) {
                $imagePath = $this->image->store('products', 'public');
                $product->image = Storage::url($imagePath);
            }
            
            $product->save();
            
            // Upload de imagens adicionais
            if (!empty($this->additionalImages)) {
                foreach ($this->additionalImages as $image) {
                    $imagePath = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url' => Storage::url($imagePath),
                        'is_main' => false,
                    ]);
                }
            }
            
            $this->showModal = false;
            $this->resetForm();
            
            $this->dispatch('notify', [
                'message' => $this->isEditing ? 'Produto atualizado com sucesso!' : 'Produto criado com sucesso!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao salvar produto: ' . $e->getMessage(),
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
            $product = Product::findOrFail($this->deleteId);
            
            // Excluir imagens adicionais
            foreach ($product->images as $image) {
                // Remover arquivo físico
                $path = str_replace('/storage', 'public', $image->url);
                Storage::delete($path);
                
                // Excluir registro
                $image->delete();
            }
            
            // Remover imagem principal
            if ($product->image) {
                $path = str_replace('/storage', 'public', $product->image);
                Storage::delete($path);
            }
            
            $product->delete();
            
            $this->confirmingDelete = false;
            $this->deleteId = null;
            
            $this->dispatch('notify', [
                'message' => 'Produto excluído com sucesso!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao excluir produto: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function resetForm()
    {
        $this->productId = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->salePrice = '';
        $this->stock = 0;
        $this->categoryId = '';
        $this->featured = false;
        $this->status = 'active';
        $this->sku = '';
        $this->weight = '';
        $this->color = '';
        $this->saleStartsAt = null;
        $this->saleEndsAt = null;
        $this->image = null;
        $this->additionalImages = [];
        
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = Product::query();
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }
        
        $query->orderBy($this->sortBy, $this->sortDirection);
        
        $products = $query->paginate($this->perPage);
        $categories = Category::orderBy('name')->get();
        
        return view('livewire.admin.product-manager', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
