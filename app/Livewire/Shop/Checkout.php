<?php

namespace App\Livewire\Shop;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Token;
use Stripe\Exception\CardException;

class Checkout extends Component
{
    public $cart;
    public $shippingAddress = [];
    public $paymentMethod = 'credit_card';
    public $notes = '';

    // Campos do endereço
    public $address;
    public $city;
    public $state;
    public $zipCode;
    public $country = 'Brasil';
    public $phone;

    // Token do Stripe
    public $stripeToken;

    protected $rules = [
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'zipCode' => 'required|string|max:20',
        'country' => 'required|string|max:100',
        'phone' => 'required|string|max:20',
        'paymentMethod' => 'required|in:credit_card,pix,boleto',
        'notes' => 'nullable|string|max:500',
    ];

    protected $validationAttributes = [
        'address' => 'endereço',
        'city' => 'cidade',
        'state' => 'estado',
        'zipCode' => 'CEP',
        'country' => 'país',
        'phone' => 'telefone',
        'paymentMethod' => 'método de pagamento',
        'notes' => 'observações',
    ];

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->loadCart();

        if ($this->cart->items->isEmpty()) {
            return redirect()->route('shop.cart');
        }

        // Carregar endereço salvo, se existir
        $savedAddress = ShippingAddress::where('user_id', Auth::id())->latest()->first();

        if ($savedAddress) {
            $this->address = $savedAddress->address;
            $this->city = $savedAddress->city;
            $this->state = $savedAddress->state;
            $this->zipCode = $savedAddress->zip_code;
            $this->country = $savedAddress->country;
            $this->phone = $savedAddress->phone;
        }
    }

    protected function loadCart()
    {
        $this->cart = Cart::where('user_id', Auth::id())->first();

        if (!$this->cart) {
            return redirect()->route('shop.cart');
        }

        // Carregar relacionamentos
        $this->cart->load('items.product');
    }

    public function placeOrder()
    {
        $this->validate();

        // Validações adicionais para cartão de crédito
        if ($this->paymentMethod === 'credit_card') {
            $this->validate([
                'stripeToken' => 'required|string',
            ], [], [
                'stripeToken' => 'token do cartão',
            ]);
        }

        try {
            DB::beginTransaction();

            // Salvar endereço
            $shippingAddress = [
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zipCode,
                'country' => $this->country,
                'phone' => $this->phone,
            ];

            // Salvar ou atualizar endereço do usuário
            ShippingAddress::updateOrCreate(
                ['user_id' => Auth::id()],
                $shippingAddress
            );

            // Criar pedido
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $this->cart->total,
                'discount' => $this->cart->discount,
                'status' => Order::STATUS_PENDING,
                'payment_method' => $this->paymentMethod,
                'shipping_address' => $shippingAddress,
                'notes' => $this->notes,
                'coupon_id' => $this->cart->coupon_id,
            ]);

            // Adicionar itens ao pedido
            foreach ($this->cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'options' => $item->options,
                ]);

                // Atualizar estoque do produto
                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();
            }

            // Processar pagamento com Stripe
            if ($this->paymentMethod === 'credit_card') {
                try {
                    // Configurar a chave secreta do Stripe
                    Stripe::setApiKey(config('cashier.secret'));

                    // Criar o pagamento
                    $charge = Charge::create([
                        'amount' => (int)($this->cart->getTotalWithDiscount() * 100), // Valor em centavos
                        'currency' => 'brl',
                        'source' => $this->stripeToken,
                        'description' => 'Pedido #' . $order->id,
                        'metadata' => [
                            'order_id' => $order->id,
                            'customer_email' => Auth::user()->email,
                        ],
                    ]);

                    // Atualizar o pedido com as informações do pagamento
                    $order->payment_id = $charge->id;
                    $order->status = Order::STATUS_PROCESSING;
                    $order->save();

                } catch (CardException $e) {
                    DB::rollBack();

                    $this->dispatch('notify', [
                        'message' => 'Erro no pagamento: ' . $e->getMessage(),
                        'type' => 'error'
                    ]);

                    return;
                }
            } else {
                // Para outros métodos de pagamento (PIX, boleto)
                $paymentId = 'PAY-' . strtoupper(substr(md5(uniqid()), 0, 10));
                $order->payment_id = $paymentId;
                $order->save();
            }

            // Limpar carrinho
            $this->cart->clear();

            DB::commit();

            // Redirecionar para página de sucesso
            return redirect()->route('shop.order.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('notify', [
                'message' => 'Erro ao processar o pedido: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.shop.checkout');
    }
}
