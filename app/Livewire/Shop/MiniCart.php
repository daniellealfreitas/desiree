<?php

namespace App\Livewire\Shop;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\On;

class MiniCart extends Component
{
    public $cart;
    public $itemCount = 0;
    public $totalAmount = 0;

    // Livewire 3 usa atributos para listeners
    #[On('add-to-cart')]
    public function handleAddToCart($data = null)
    {
        logger()->debug('MiniCart: Evento add-to-cart recebido', ['data' => $data, 'component_id' => $this->getId()]);

        // Se não recebeu dados, não pode continuar
        if (!$data || !is_array($data) || !isset($data['productId'])) {
            logger()->error('MiniCart: Dados inválidos recebidos no evento add-to-cart', ['data' => $data]);
            $this->dispatch('notify', [
                'message' => 'Erro ao adicionar produto ao carrinho: dados inválidos',
                'type' => 'error'
            ]);

            // Adicionar notificação direta para depuração
            session()->flash('error', 'Erro ao adicionar produto ao carrinho: dados inválidos');

            return false;
        }

        // Continua com o processamento normal
        try {
            logger()->debug('MiniCart: Chamando processAddToCart', ['product_id' => $data['productId']]);

            // Adicionar notificação direta para depuração
            session()->flash('info', 'Tentando adicionar produto ' . $data['productId'] . ' ao carrinho');

            // Resto do código será mantido no método abaixo
            $result = $this->processAddToCart($data);

            logger()->debug('MiniCart: Resultado do processAddToCart', ['result' => $result]);

            return $result;
        } catch (\Exception $e) {
            logger()->error('MiniCart: Erro ao processar add-to-cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Adicionar notificação direta para depuração
            session()->flash('error', 'Erro ao processar add-to-cart: ' . $e->getMessage());

            return false;
        }
    }

    #[On('cart-updated')]
    public function onCartUpdated()
    {
        logger()->info('MiniCart: Evento cart-updated recebido');
        $this->loadCart();
    }

    public function mount()
    {
        $this->loadCart();
        $this->dispatch('mini-cart-mounted');
        logger()->info('MiniCart montado', ['user_id' => Auth::id(), 'session_id' => Session::getId()]);
    }

    public function loadCart()
    {
        logger()->info('MiniCart: Carregando carrinho', ['user_id' => Auth::id(), 'session_id' => Session::getId()]);

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

        // Atualizar contadores
        $this->updateCounters();

        logger()->info('MiniCart: Carrinho carregado', [
            'cart_id' => $this->cart->id,
            'item_count' => $this->itemCount,
            'total_amount' => $this->totalAmount
        ]);
    }

    protected function processAddToCart($data)
    {
        try {
            logger()->info('MiniCart: processAddToCart chamado', ['data' => $data]);

            $productId = $data['productId'];
            $quantity = $data['quantity'] ?? 1;
            $price = $data['price'] ?? null;
            $options = $data['options'] ?? [];

            $product = Product::findOrFail($productId);

            // Se o preço não foi fornecido, usar o preço atual do produto
            if ($price === null) {
                $price = $product->getCurrentPrice();
            }

            logger()->info('MiniCart: Dados do produto', [
                'product_id' => $productId,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $price
            ]);

            // Verificar se o produto está disponível
            if ($product->stock <= 0) {
                $this->dispatch('notify', [
                    'message' => 'Produto indisponível no momento!',
                    'type' => 'error'
                ]);
                return false;
            }

            // Verificar se a quantidade solicitada está disponível
            if ($product->stock < $quantity) {
                $this->dispatch('notify', [
                    'message' => 'Quantidade indisponível em estoque! Apenas ' . $product->stock . ' unidades disponíveis.',
                    'type' => 'error'
                ]);
                return false;
            }

            // Verificar se o produto já está no carrinho
            $cartItem = $this->cart->items()
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // Atualizar quantidade
                $cartItem->quantity += $quantity;
                $cartItem->save();
                logger()->info('MiniCart: Quantidade atualizada', ['item_id' => $cartItem->id, 'nova_quantidade' => $cartItem->quantity]);
            } else {
                // Adicionar novo item
                $cartItem = $this->cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'options' => $options
                ]);
                logger()->info('MiniCart: Novo item adicionado', ['item_id' => $cartItem->id]);
            }

            // Recalcular total
            if (method_exists($this->cart, 'calculateTotal')) {
                $this->cart->calculateTotal();
            } else {
                // Cálculo manual se o método não existir
                $total = $this->cart->items->sum(function ($item) {
                    return $item->quantity * $item->price;
                });
                $this->cart->total = $total;
                $this->cart->save();
            }

            // Recarregar carrinho
            $this->loadCart();

            // Registrar informações de sucesso
            logger()->info('MiniCart: Produto adicionado com sucesso', [
                'cart_id' => $this->cart->id,
                'item_count' => $this->itemCount,
                'total' => $this->totalAmount
            ]);

            // Disparar evento para atualizar outros componentes do carrinho
            $this->dispatch('cart-updated');

            // Notificar o usuário com o novo tipo 'success' para mostrar botões de ação
            $this->dispatch('notify', [
                'message' => 'Produto adicionado ao carrinho!',
                'type' => 'success',
                'timeout' => 8000 // Tempo maior para permitir que o usuário veja os botões
            ]);

            // Adicionar notificação direta para depuração
            session()->flash('success', 'Produto adicionado ao carrinho com sucesso!');

            // Redirecionar para o carrinho
            // Nota: Como este método é chamado por outros componentes, não podemos redirecionar aqui
            // O redirecionamento deve ser feito pelo componente que chamou este método

            return true;
        } catch (\Exception $e) {
            logger()->error('MiniCart: Erro ao adicionar produto ao carrinho', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('notify', [
                'message' => 'Erro ao adicionar produto ao carrinho: ' . $e->getMessage(),
                'type' => 'error'
            ]);

            return false;
        }
    }

    public function removeItem($itemId)
    {
        try {
            logger()->info('MiniCart: Removendo item', ['item_id' => $itemId]);

            $cartItem = CartItem::findOrFail($itemId);
            $cartItem->delete();

            // Recalcular total
            if (method_exists($this->cart, 'calculateTotal')) {
                $this->cart->calculateTotal();
            } else {
                // Cálculo manual se o método não existir
                $total = $this->cart->items->sum(function ($item) {
                    return $item->quantity * $item->price;
                });
                $this->cart->total = $total;
                $this->cart->save();
            }

            // Recarregar carrinho
            $this->loadCart();

            $this->dispatch('notify', [
                'message' => 'Item removido do carrinho!',
                'type' => 'success'
            ]);

            // Disparar evento para atualizar outros componentes do carrinho
            $this->dispatch('cart-updated');

            logger()->info('MiniCart: Item removido com sucesso', [
                'cart_id' => $this->cart->id,
                'item_count' => $this->itemCount,
                'total' => $this->totalAmount
            ]);
        } catch (\Exception $e) {
            logger()->error('MiniCart: Erro ao remover item do carrinho', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('notify', [
                'message' => 'Erro ao remover item do carrinho: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    protected function updateCounters()
    {
        $this->itemCount = $this->cart->items->sum('quantity');
        $this->totalAmount = $this->cart->getTotalWithDiscount();
    }

    public function render()
    {
        return view('livewire.shop.mini-cart');
    }
}
