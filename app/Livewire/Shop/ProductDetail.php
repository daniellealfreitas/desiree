<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Livewire\Component;

class ProductDetail extends Component
{
    public $slug;
    public $product;
    public $quantity = 1;
    public $selectedOptions = [];

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->product = Product::where('slug', $slug)->firstOrFail();
        
        // Inicializar opções se o produto tiver
        if ($this->product->options) {
            foreach ($this->product->options as $option => $values) {
                $this->selectedOptions[$option] = $values[0] ?? null;
            }
        }
    }

    public function incrementQuantity()
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if ($this->product->stock < $this->quantity) {
            $this->dispatch('notify', [
                'message' => 'Quantidade indisponível em estoque!',
                'type' => 'error'
            ]);
            return;
        }
        
        // Emitir evento para o componente do carrinho
        $this->dispatch('add-to-cart', [
            'productId' => $this->product->id,
            'quantity' => $this->quantity,
            'price' => $this->product->getCurrentPrice(),
            'options' => $this->selectedOptions
        ]);
        
        $this->dispatch('notify', [
            'message' => 'Produto adicionado ao carrinho!',
            'type' => 'success'
        ]);
        
        // Resetar quantidade
        $this->quantity = 1;
    }

    public function addToWishlist()
    {
        // Verificar se o usuário está autenticado
        if (!auth()->check()) {
            $this->dispatch('notify', [
                'message' => 'Você precisa estar logado para adicionar à lista de desejos!',
                'type' => 'error'
            ]);
            return;
        }
        
        $user = auth()->user();
        
        // Verificar se o produto já está na lista de desejos
        if ($user->wishlistedProducts()->where('product_id', $this->product->id)->exists()) {
            // Remover da lista de desejos
            $user->wishlistedProducts()->detach($this->product->id);
            
            $this->dispatch('notify', [
                'message' => 'Produto removido da lista de desejos!',
                'type' => 'success'
            ]);
        } else {
            // Adicionar à lista de desejos
            $user->wishlistedProducts()->attach($this->product->id);
            
            $this->dispatch('notify', [
                'message' => 'Produto adicionado à lista de desejos!',
                'type' => 'success'
            ]);
        }
    }

    public function render()
    {
        $relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->active()
            ->limit(4)
            ->get();
            
        $isInWishlist = auth()->check() && 
            auth()->user()->wishlistedProducts()->where('product_id', $this->product->id)->exists();
            
        return view('livewire.shop.product-detail', [
            'relatedProducts' => $relatedProducts,
            'isInWishlist' => $isInWishlist
        ]);
    }
}
