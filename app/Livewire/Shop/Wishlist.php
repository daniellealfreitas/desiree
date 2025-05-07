<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Wishlist extends Component
{
    use WithPagination;
    
    public function removeFromWishlist($productId)
    {
        auth()->user()->wishlistedProducts()->detach($productId);
        
        $this->dispatch('notify', [
            'message' => 'Produto removido da lista de desejos!',
            'type' => 'success'
        ]);
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
        $wishlist = auth()->user()->wishlistedProducts()->paginate(12);
        
        return view('livewire.shop.wishlist', [
            'wishlist' => $wishlist
        ]);
    }
}
