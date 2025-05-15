<?php

namespace App\Livewire\Shop;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ShoppingCart extends Component
{
    public $cart;
    public $couponCode = '';
    public $couponError = '';
    public $couponSuccess = '';

    // Livewire 3 usa o método getListeners() em vez de $listeners
    protected function getListeners()
    {
        return [
            'add-to-cart' => 'addToCart',
            'cart-updated' => 'loadCart'
        ];
    }

    // Método alternativo para garantir que os eventos sejam capturados
    public function boot()
    {
        $this->dispatch('cart-component-loaded');
    }

    public function mount()
    {
        $this->loadCart();
    }

    protected function loadCart()
    {
        if (Auth::check()) {
            // Usuário logado - buscar ou criar carrinho
            $this->cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => Session::getId()]
            );
        } else {
            // Usuário não logado - usar session_id
            $sessionId = Session::getId();
            $this->cart = Cart::firstOrCreate(
                ['session_id' => $sessionId, 'user_id' => null],
                []
            );
        }

        // Carregar relacionamentos
        $this->cart->load('items.product');
    }

    public function addToCart($data)
    {
        try {
            logger()->info('ShoppingCart: addToCart chamado', ['data' => $data]);

            $productId = $data['productId'];
            $quantity = $data['quantity'];
            $price = $data['price'];
            $options = $data['options'] ?? [];

            $product = Product::findOrFail($productId);

            // Verificar estoque
            if ($product->stock < $quantity) {
                $this->dispatch('notify', [
                    'message' => 'Quantidade indisponível em estoque!',
                    'type' => 'error'
                ]);
                return;
            }

            // Verificar se o produto já está no carrinho
            $cartItem = $this->cart->items()
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // Atualizar quantidade
                $cartItem->quantity += $quantity;
                $cartItem->save();
                logger()->info('ShoppingCart: Quantidade atualizada', ['item_id' => $cartItem->id, 'nova_quantidade' => $cartItem->quantity]);
            } else {
                // Adicionar novo item
                $cartItem = $this->cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'options' => $options
                ]);
                logger()->info('ShoppingCart: Novo item adicionado', ['item_id' => $cartItem->id]);
            }

            // Recalcular total
            $this->cart->calculateTotal();

            // Recarregar carrinho
            $this->loadCart();

            // Notificar o usuário com o novo tipo 'cart' para mostrar botões de ação
            $this->dispatch('notify', [
                'message' => 'Produto adicionado ao carrinho!',
                'type' => 'cart',
                'timeout' => 8000 // Tempo maior para permitir que o usuário veja os botões
            ]);

            // Notificar outros componentes que o carrinho foi atualizado
            $this->dispatch('cart-updated');

            logger()->info('ShoppingCart: Produto adicionado com sucesso', [
                'cart_id' => $this->cart->id,
                'total' => $this->cart->total
            ]);
        } catch (\Exception $e) {
            logger()->error('ShoppingCart: Erro ao adicionar produto ao carrinho', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('notify', [
                'message' => 'Erro ao adicionar produto ao carrinho: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function updateQuantity($itemId, $quantity)
    {
        $cartItem = CartItem::findOrFail($itemId);

        // Verificar estoque
        if ($cartItem->product->stock < $quantity) {
            $this->dispatch('notify', [
                'message' => 'Quantidade indisponível em estoque!',
                'type' => 'error'
            ]);
            return;
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        // Recalcular total
        $this->cart->calculateTotal();

        // Recarregar carrinho
        $this->loadCart();
    }

    public function removeItem($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $cartItem->delete();

        // Recalcular total
        $this->cart->calculateTotal();

        // Recarregar carrinho
        $this->loadCart();

        $this->dispatch('notify', [
            'message' => 'Item removido do carrinho!',
            'type' => 'success'
        ]);

        // Notificar outros componentes que o carrinho foi atualizado
        $this->dispatch('cart-updated');
    }

    public function clearCart()
    {
        $this->cart->clear();
        $this->loadCart();

        $this->dispatch('notify', [
            'message' => 'Carrinho esvaziado com sucesso!',
            'type' => 'success'
        ]);

        // Notificar outros componentes que o carrinho foi atualizado
        $this->dispatch('cart-updated');
    }

    public function applyCoupon()
    {
        $this->couponError = '';
        $this->couponSuccess = '';

        if (empty($this->couponCode)) {
            $this->couponError = 'Informe um código de cupom válido.';
            return;
        }

        $coupon = Coupon::where('code', $this->couponCode)
            ->where('active', true)
            ->first();

        if (!$coupon) {
            $this->couponError = 'Cupom inválido ou expirado.';
            return;
        }

        if ($coupon->expires_at && $coupon->expires_at < now()) {
            $this->couponError = 'Este cupom expirou.';
            return;
        }

        if ($coupon->usage_limit && $coupon->used >= $coupon->usage_limit) {
            $this->couponError = 'Este cupom atingiu o limite de uso.';
            return;
        }

        // Aplicar cupom
        $this->cart->applyCoupon($coupon);
        $this->loadCart();

        $this->couponSuccess = 'Cupom aplicado com sucesso!';
        $this->couponCode = '';
    }

    public function removeCoupon()
    {
        $this->cart->removeCoupon();
        $this->loadCart();

        $this->couponSuccess = 'Cupom removido com sucesso!';
        $this->couponError = '';
    }

    public function checkout()
    {
        if ($this->cart->items->isEmpty()) {
            $this->dispatch('notify', [
                'message' => 'Seu carrinho está vazio!',
                'type' => 'error'
            ]);
            return;
        }

        return redirect()->route('shop.checkout');
    }

    public function render()
    {
        return view('livewire.shop.shopping-cart');
    }
}
