<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Rota para obter um produto pelo slug
 */
Route::get('/products/slug/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->first();

    if (!$product) {
        return response()->json(['error' => 'Produto não encontrado'], 404);
    }

    return response()->json([
        'id' => $product->id,
        'name' => $product->name,
        'price' => $product->getCurrentPrice()
    ]);
});

/**
 * Rota para adicionar um produto ao carrinho
 */
Route::post('/cart/add', function (Request $request) {
    try {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $options = $request->input('options', []);

        $product = Product::findOrFail($productId);

        // Verificar estoque
        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Quantidade indisponível em estoque!'
            ]);
        }

        // Obter o carrinho atual
        $userId = auth()->id();
        $sessionId = session()->getId();

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

        // Verificar se o produto já está no carrinho
        $cartItem = $cart->items()
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Atualizar quantidade
            $cartItem->quantity += $quantity;
            $cartItem->save();

            // Log para depuração
            \Log::info('API: Quantidade atualizada', [
                'item_id' => $cartItem->id,
                'nova_quantidade' => $cartItem->quantity
            ]);
        } else {
            // Adicionar novo item
            $cartItem = $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->getCurrentPrice(),
                'options' => $options
            ]);

            // Log para depuração
            \Log::info('API: Novo item adicionado', [
                'item_id' => $cartItem->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->getCurrentPrice()
            ]);
        }

        // Recalcular total
        if (method_exists($cart, 'calculateTotal')) {
            $cart->calculateTotal();
        } else {
            // Cálculo manual
            $total = $cart->items->sum(function ($item) {
                return $item->quantity * $item->price;
            });
            $cart->total = $total;
            $cart->save();
        }

        // Log para depuração
        \Log::info('API: Produto adicionado com sucesso', [
            'cart_id' => $cart->id,
            'total' => $cart->total,
            'item_count' => $cart->items->sum('quantity')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produto adicionado ao carrinho!',
            'cart' => [
                'id' => $cart->id,
                'total' => $cart->total,
                'item_count' => $cart->items->sum('quantity')
            ]
        ]);
    } catch (\Exception $e) {
        // Log para depuração
        \Log::error('API: Erro ao adicionar produto ao carrinho', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erro ao adicionar produto ao carrinho: ' . $e->getMessage()
        ], 500);
    }
});
