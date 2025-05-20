<?php

namespace App\Livewire\Shop;

use App\Models\Cart;
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
    public $showOnlyDigital = false;
    public $showOnlyPhysical = false;
    public $showOnlyAvailable = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 12],
        'priceMin' => ['except' => null],
        'priceMax' => ['except' => null],
        'showOnlyOnSale' => ['except' => false],
        'showOnlyDigital' => ['except' => false],
        'showOnlyPhysical' => ['except' => false],
        'showOnlyAvailable' => ['except' => true],
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

    public function updatingShowOnlyDigital()
    {
        $this->resetPage();
        if ($this->showOnlyDigital) {
            $this->showOnlyPhysical = false;
        }
    }

    public function updatingShowOnlyPhysical()
    {
        $this->resetPage();
        if ($this->showOnlyPhysical) {
            $this->showOnlyDigital = false;
        }
    }

    public function updatingShowOnlyAvailable()
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
        try {
            logger()->debug('ProductList: Adicionando produto ao carrinho', ['product_id' => $productId]);

            $product = Product::findOrFail($productId);

            // Verificar se o produto está disponível
            if ($product->stock <= 0) {
                $this->dispatch('notify', [
                    'message' => 'Produto indisponível no momento!',
                    'type' => 'error'
                ]);
                return;
            }

            // Tentar obter o componente MiniCart
            $miniCart = $this->getComponent('shop.mini-cart');

            if ($miniCart) {
                logger()->debug('ProductList: MiniCart encontrado, chamando método diretamente');

                // Chamar o método diretamente
                $result = $miniCart->handleAddToCart([
                    'productId' => $productId,
                    'quantity' => 1,
                    'price' => $product->getCurrentPrice()
                ]);

                logger()->debug('ProductList: Resultado da chamada direta ao MiniCart', ['result' => $result]);

                if ($result) {
                    // Notificar o usuário com o novo tipo 'cart' para mostrar botões de ação
                    $this->dispatch('notify', [
                        'message' => 'Produto adicionado ao carrinho!',
                        'type' => 'cart',
                        'timeout' => 8000 // Tempo maior para permitir que o usuário veja os botões
                    ]);

                    // Adicionar notificação direta para depuração
                    session()->flash('success', 'Produto adicionado ao carrinho com sucesso!');

                    // Redirecionar para o carrinho
                    return $this->redirect(route('shop.cart'));
                }
            }

            // Se não conseguiu usar o MiniCart ou se falhou, usar a abordagem original
            logger()->debug('ProductList: Usando abordagem original');

            // Obter o carrinho atual
            $cart = $this->getCurrentCart();

            if ($cart) {
                // Verificar se o produto já está no carrinho
                $cartItem = $cart->items()
                    ->where('product_id', $productId)
                    ->first();

                if ($cartItem) {
                    // Atualizar quantidade
                    $cartItem->quantity += 1;
                    $cartItem->save();
                    logger()->debug('ProductList: Quantidade atualizada', ['item_id' => $cartItem->id, 'nova_quantidade' => $cartItem->quantity]);
                } else {
                    // Adicionar novo item
                    $cartItem = $cart->items()->create([
                        'product_id' => $productId,
                        'quantity' => 1,
                        'price' => $product->getCurrentPrice(),
                        'options' => []
                    ]);
                    logger()->debug('ProductList: Novo item adicionado', ['item_id' => $cartItem->id]);
                }

                // Recalcular total
                $cart->calculateTotal();

                // Disparar evento para atualizar outros componentes
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
                return $this->redirect(route('shop.cart'));
            } else {
                logger()->error('ProductList: Carrinho não encontrado');
                $this->dispatch('notify', [
                    'message' => 'Erro ao adicionar produto ao carrinho: carrinho não encontrado',
                    'type' => 'error'
                ]);
            }
        } catch (\Exception $e) {
            logger()->error('ProductList: Erro ao adicionar ao carrinho', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'product_id' => $productId
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

    /**
     * Adiciona ou remove um produto da lista de desejos
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

        if ($this->showOnlyDigital) {
            $query->where('is_digital', true);
        }

        if ($this->showOnlyPhysical) {
            $query->where('is_digital', false);
        }

        if ($this->showOnlyAvailable) {
            $query->available(); // Usa o escopo que criamos no modelo Product
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        $products = $query->paginate($this->perPage);
        $categories = Category::active()->get();

        return view('livewire.shop.product-list', [
            'products' => $products,
            'categories' => $categories
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