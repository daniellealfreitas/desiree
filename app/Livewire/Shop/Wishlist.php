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
        try {
            logger()->debug('Wishlist: Adicionando produto ao carrinho', ['product_id' => $productId]);

            $product = Product::findOrFail($productId);

            // Verificar se o produto está disponível
            if ($product->stock <= 0) {
                $this->dispatch('notify', [
                    'message' => 'Produto indisponível no momento!',
                    'type' => 'error'
                ]);
                return;
            }

            // Obter o componente MiniCart diretamente
            $miniCart = $this->getMiniCartComponent();

            if ($miniCart) {
                logger()->debug('Wishlist: MiniCart encontrado, chamando método diretamente');

                // Chamar o método diretamente
                $result = $miniCart->handleAddToCart([
                    'productId' => $productId,
                    'quantity' => 1,
                    'price' => $product->getCurrentPrice()
                ]);

                logger()->debug('Wishlist: Resultado da chamada direta', ['result' => $result]);

                if (!$result) {
                    // Se falhou, pode ser por causa do estoque
                    return;
                }
            } else {
                logger()->debug('Wishlist: MiniCart não encontrado, usando dispatch');

                // Emitir evento para o componente do carrinho (abordagem original)
                $this->dispatch('add-to-cart', [
                    'productId' => $productId,
                    'quantity' => 1,
                    'price' => $product->getCurrentPrice()
                ]);

                // Adicionar um pequeno atraso e disparar o evento cart-updated como fallback
                $this->dispatch('cart-updated');
            }

            // Notificar o usuário com o novo tipo 'cart' para mostrar botões de ação
            $this->dispatch('notify', [
                'message' => 'Produto adicionado ao carrinho!',
                'type' => 'cart',
                'timeout' => 8000 // Tempo maior para permitir que o usuário veja os botões
            ]);

            // Adicionar notificação direta para depuração
            session()->flash('success', 'Produto adicionado ao carrinho via Wishlist');

        } catch (\Exception $e) {
            logger()->error('Wishlist: Erro ao adicionar produto ao carrinho', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('notify', [
                'message' => 'Erro ao adicionar produto ao carrinho: ' . $e->getMessage(),
                'type' => 'error'
            ]);

            // Adicionar notificação direta para depuração
            session()->flash('error', 'Erro ao adicionar produto ao carrinho: ' . $e->getMessage());
        }
    }

    /**
     * Tenta obter o componente MiniCart
     */
    protected function getMiniCartComponent()
    {
        try {
            // Tentar obter o componente MiniCart
            return \Livewire\Livewire::getInstance()->getComponent('shop.mini-cart');
        } catch (\Exception $e) {
            logger()->warning('Wishlist: Não foi possível obter o componente MiniCart', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function render()
    {
        $wishlist = auth()->user()->wishlistedProducts()->paginate(12);

        return view('livewire.shop.wishlist', [
            'wishlist' => $wishlist
        ]);
    }
}
