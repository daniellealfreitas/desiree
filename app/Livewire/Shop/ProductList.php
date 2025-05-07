<?php

namespace App\Livewire\Shop;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryId = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 12;
    public $priceMin = null;
    public $priceMax = null;
    public $showOnlyOnSale = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 12],
        'priceMin' => ['except' => null],
        'priceMax' => ['except' => null],
        'showOnlyOnSale' => ['except' => false],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function updatingPriceMin()
    {
        $this->resetPage();
    }

    public function updatingPriceMax()
    {
        $this->resetPage();
    }

    public function updatingShowOnlyOnSale()
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

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        
        // Emitir evento para o componente do carrinho
        $this->dispatch('add-to-cart', [
            'productId' => $productId,
            'quantity' => 1,
            'price' => $product->getCurrentPrice()
        ]);
        
        $this->dispatch('notify', [
            'message' => 'Produto adicionado ao carrinho!',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        $query = Product::query()->active();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->priceMin) {
            $query->where(function ($q) {
                $q->where('price', '>=', $this->priceMin)
                  ->orWhere('sale_price', '>=', $this->priceMin);
            });
        }

        if ($this->priceMax) {
            $query->where(function ($q) {
                $q->where('price', '<=', $this->priceMax)
                  ->orWhere('sale_price', '<=', $this->priceMax);
            });
        }

        if ($this->showOnlyOnSale) {
            $query->onSale();
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        $products = $query->paginate($this->perPage);
        $categories = Category::active()->get();

        return view('livewire.shop.product-list', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
