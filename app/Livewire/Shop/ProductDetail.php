<?php

namespace App\Livewire\Shop;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
        try {
            logger()->debug('ProductDetail: Adicionando produto ao carrinho', [
                'product_id' => $this->product->id,
                'quantity' => $this->quantity
            ]);

            // Verificar se o produto está disponível
            if ($this->product->stock <= 0) {
                $this->dispatch('notify', [
                    'message' => 'Produto indisponível no momento!',
                    'type' => 'error'
                ]);
                return;
            }

            // Verificar se a quantidade solicitada está disponível
            if ($this->product->stock < $this->quantity) {
                $this->dispatch('notify', [
                    'message' => 'Quantidade indisponível em estoque!',
                    'type' => 'error'
                ]);
                return;
            }

            // Tentar obter o componente MiniCart
            $miniCart = $this->getComponent('shop.mini-cart');

            if ($miniCart) {
                logger()->debug('ProductDetail: MiniCart encontrado, chamando método diretamente');

                // Preparar as opções selecionadas
                $options = !empty($this->selectedOptions) ? $this->selectedOptions : [];

                // Chamar o método diretamente
                $result = $miniCart->handleAddToCart([
                    'productId' => $this->product->id,
                    'quantity' => $this->quantity,
                    'price' => $this->product->getCurrentPrice(),
                    'options' => $options
                ]);

                logger()->debug('ProductDetail: Resultado da chamada direta ao MiniCart', ['result' => $result]);

                if ($result) {
                    // Resetar quantidade
                    $this->quantity = 1;

                    // Notificar o usuário com o novo tipo 'success' para mostrar botões de ação
                    $this->dispatch('notify', [
                        'message' => 'Produto adicionado ao carrinho!',
                        'type' => 'success',
                        'timeout' => 8000 // Tempo maior para permitir que o usuário veja os botões
                    ]);

                    // Adicionar notificação direta para depuração
                    session()->flash('success', 'Produto adicionado ao carrinho com sucesso!');

                    // Redirecionar para a loja
                    return $this->redirect(route('shop.cart'));
                }
            }

            // Se não conseguiu usar o MiniCart ou se falhou, usar a abordagem original
            logger()->debug('ProductDetail: Usando abordagem original');

            // Obter o carrinho atual
            $cart = $this->getCurrentCart();

            if ($cart) {
                // Verificar se o produto já está no carrinho
                $cartItem = $cart->items()
                    ->where('product_id', $this->product->id)
                    ->first();

                if ($cartItem) {
                    // Atualizar quantidade
                    $cartItem->quantity += $this->quantity;
                    $cartItem->save();
                    logger()->debug('ProductDetail: Quantidade atualizada', ['item_id' => $cartItem->id, 'nova_quantidade' => $cartItem->quantity]);
                } else {
                    // Preparar as opções selecionadas
                    $options = !empty($this->selectedOptions) ? $this->selectedOptions : [];

                    // Adicionar novo item
                    $cartItem = $cart->items()->create([
                        'product_id' => $this->product->id,
                        'quantity' => $this->quantity,
                        'price' => $this->product->getCurrentPrice(),
                        'options' => $options
                    ]);
                    logger()->debug('ProductDetail: Novo item adicionado', ['item_id' => $cartItem->id]);
                }

                // Recalcular total
                $cart->calculateTotal();

                // Disparar evento para atualizar outros componentes
                $this->dispatch('cart-updated');

                // Resetar quantidade
                $this->quantity = 1;

                // Notificar o usuário com o novo tipo 'success' para mostrar botões de ação
                $this->dispatch('notify', [
                    'message' => 'Produto adicionado ao carrinho!',
                    'type' => 'success',
                    'timeout' => 8000 // Tempo maior para permitir que o usuário veja os botões
                ]);

                // Adicionar notificação direta para depuração
                session()->flash('success', 'Produto adicionado ao carrinho com sucesso!');

                // Redirecionar para o carrinho
                return $this->redirect(route('shop.cart'));
            } else {
                logger()->error('ProductDetail: Carrinho não encontrado');
                $this->dispatch('notify', [
                    'message' => 'Erro ao adicionar produto ao carrinho: carrinho não encontrado',
                    'type' => 'error'
                ]);
            }
        } catch (\Exception $e) {
            logger()->error('ProductDetail: Erro ao adicionar ao carrinho', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'product_id' => $this->product->id
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
     * Obtém o carrinho atual do usuário ou da sessão
     */
    protected function getCurrentCart()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        // Buscar carrinho do usuário ou da sessão
        $cart = Cart::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->first();

        // Se não existir, criar um novo
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'total' => 0
            ]);
        }

        return $cart;
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

    /**
     * Adiciona ou remove um produto relacionado da lista de desejos
     */
    public function toggleWishlist($productId)
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
        if ($user->wishlistedProducts()->where('product_id', $productId)->exists()) {
            // Remover da lista de desejos
            $user->wishlistedProducts()->detach($productId);

            $this->dispatch('notify', [
                'message' => 'Produto removido da lista de desejos!',
                'type' => 'success'
            ]);
        } else {
            // Adicionar à lista de desejos
            $user->wishlistedProducts()->attach($productId);

            $this->dispatch('notify', [
                'message' => 'Produto adicionado à lista de desejos!',
                'type' => 'success'
            ]);
        }
    }

    /**
     * Verifica se um produto está na lista de desejos do usuário
     */
    public function isInWishlist($productId)
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->wishlistedProducts()->where('product_id', $productId)->exists();
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

    /**
     * Tenta obter um componente Livewire pelo nome
     */
    protected function getComponent($name)
    {
        try {
            // Verificar se o componente está registrado no AppServiceProvider
            $componentClass = null;

            // Obter o componente usando o método correto para Livewire 3
            if (class_exists("App\\Livewire\\" . str_replace('.', '\\', ucwords($name, '.')))) {
                $componentClass = "App\\Livewire\\" . str_replace('.', '\\', ucwords($name, '.'));
            } elseif (class_exists("App\\Livewire\\" . str_replace(['.', '-'], ['\\', ''], ucwords($name, '.-')))) {
                $componentClass = "App\\Livewire\\" . str_replace(['.', '-'], ['\\', ''], ucwords($name, '.-'));
            } elseif (class_exists("App\\Http\\Livewire\\" . str_replace('.', '\\', ucwords($name, '.')))) {
                $componentClass = "App\\Http\\Livewire\\" . str_replace('.', '\\', ucwords($name, '.'));
            }

            // Para componentes Volt, tentar resolver de outra forma
            if (!$componentClass && app()->has('livewire')) {
                $livewire = app('livewire');

                // Verificar se existe um alias registrado
                if (method_exists($livewire, 'getComponentAliases') && ($aliases = $livewire->getComponentAliases())) {
                    if (isset($aliases[$name])) {
                        $componentClass = $aliases[$name];
                    }
                }
            }

            if (!$componentClass) {
                logger()->warning('Componente Livewire não encontrado', ['name' => $name]);
                return null;
            }

            // Criar uma instância do componente
            $instance = new $componentClass();

            // Inicializar o componente
            if (method_exists($instance, 'mount')) {
                $instance->mount();
            }

            return $instance;
        } catch (\Exception $e) {
            logger()->warning('Erro ao obter componente Livewire', [
                'name' => $name,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }
}