<?php

namespace App\Livewire\Shop;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes;
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
    public $hasPhysicalProducts = false;
    public $hasDigitalProducts = false;

    // Campos do endereço
    public $address;
    public $city;
    public $state;
    public $zipCode;
    public $country = 'Brasil';
    public $phone;

    // Token do Stripe
    public $stripeToken;

    // Wallet
    public $walletBalance = 0;

    protected function rules()
    {
        $rules = [
            'paymentMethod' => 'required|in:credit_card,pix,boleto,wallet',
            'notes' => 'nullable|string|max:500',
        ];

        // Não exigimos mais endereço, pois os produtos são retirados no local
        return $rules;
    }

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

        // Carregar saldo da carteira
        $wallet = Auth::user()->wallet;
        if (!$wallet) {
            // Criar carteira se não existir
            $wallet = \App\Models\Wallet::create([
                'user_id' => Auth::id(),
                'balance' => 0,
                'active' => true
            ]);
        }
        $this->walletBalance = $wallet->balance;
    }

    protected function loadCart()
    {
        $this->cart = Cart::where('user_id', Auth::id())->first();

        if (!$this->cart) {
            return redirect()->route('shop.cart');
        }

        // Carregar relacionamentos
        $this->cart->load('items.product');

        // Verificar se há produtos físicos ou digitais no carrinho
        $this->hasPhysicalProducts = false;
        $this->hasDigitalProducts = false;

        foreach ($this->cart->items as $item) {
            if ($item->product->is_digital) {
                $this->hasDigitalProducts = true;
            } else {
                $this->hasPhysicalProducts = true;
            }

            // Se já encontrou ambos os tipos, não precisa continuar verificando
            if ($this->hasPhysicalProducts && $this->hasDigitalProducts) {
                break;
            }
        }
    }

    public function placeOrder()
    {
        logger()->info('Iniciando processamento do pedido', [
            'user_id' => Auth::id(),
            'payment_method' => $this->paymentMethod,
            'has_stripe_token' => !empty($this->stripeToken),
            'cart_total' => $this->cart ? $this->cart->getTotalWithDiscount() : 0
        ]);

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

            // Produtos são retirados no local, não precisamos de endereço de entrega
            $shippingAddress = [
                'pickup' => true,
                'message' => 'Produto para retirada no local'
            ];

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
                $orderItem = OrderItem::create([
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

                // Se for um produto digital, criar registro de download
                if ($product->is_digital) {
                    // Criar um registro de download para cada quantidade do produto
                    for ($i = 0; $i < $item->quantity; $i++) {
                        \App\Models\ProductDownload::create([
                            'order_item_id' => $orderItem->id,
                            'user_id' => Auth::id(),
                            'product_id' => $product->id,
                            'download_count' => 0,
                            'last_download' => null,
                            'expires_at' => $product->download_expiry_days
                                ? now()->addDays($product->download_expiry_days)
                                : null,
                        ]);
                    }
                }
            }

            // Processar pagamento com Stripe
            if ($this->paymentMethod === 'credit_card') {
                try {
                    // Verificar se o token do Stripe foi recebido
                    if (empty($this->stripeToken)) {
                        throw new \Exception('Token do cartão não recebido. Por favor, tente novamente.');
                    }

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

                    // Registrar sucesso no log
                    logger()->info('Pagamento com cartão processado com sucesso', [
                        'user_id' => Auth::id(),
                        'order_id' => $order->id,
                        'charge_id' => $charge->id,
                        'amount' => $this->cart->getTotalWithDiscount()
                    ]);

                } catch (CardException $e) {
                    DB::rollBack();

                    logger()->error('Erro no processamento do cartão', [
                        'user_id' => Auth::id(),
                        'order_id' => $order->id ?? null,
                        'error' => $e->getMessage(),
                        'code' => $e->getCode()
                    ]);

                    $this->dispatch('notify', [
                        'message' => 'Erro no pagamento: ' . $e->getMessage(),
                        'type' => 'error'
                    ]);

                    // Notificar o frontend para reativar o botão
                    $this->dispatch('checkoutFailed');
                    return;
                } catch (\Exception $e) {
                    DB::rollBack();

                    logger()->error('Erro no processamento do pagamento com cartão', [
                        'user_id' => Auth::id(),
                        'order_id' => $order->id ?? null,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    $this->dispatch('notify', [
                        'message' => 'Erro ao processar o pagamento: ' . $e->getMessage(),
                        'type' => 'error'
                    ]);

                    // Notificar o frontend para reativar o botão
                    $this->dispatch('checkoutFailed');
                    return;
                }
            } elseif ($this->paymentMethod === 'wallet') {
                // Pagamento com carteira
                $user = Auth::user();

                try {
                    // Recarregar o usuário e a carteira para garantir dados atualizados
                    $user->refresh();

                    // Obter a carteira com bloqueio para atualização
                    $wallet = $user->wallet()->lockForUpdate()->first();

                    if (!$wallet) {
                        throw new \Exception('Carteira não encontrada. Por favor, tente novamente.');
                    }

                    // Atualizar o saldo da carteira na interface
                    $this->walletBalance = $wallet->balance;

                    $total = $this->cart->getTotalWithDiscount();

                    // Verificar se o usuário tem saldo suficiente
                    if ($wallet->balance < $total) {
                        throw new \Exception('Saldo insuficiente na carteira. Adicione fundos ou escolha outro método de pagamento.');
                    }

                    // Atualizar o método de pagamento do pedido
                    $order->payment_method = 'wallet';
                    $order->save();

                    // Processar pagamento com carteira
                    $transaction = $wallet->subtractFunds(
                        $total,
                        'purchase',
                        'Compra #' . $order->id,
                        $order->id,
                        Order::class
                    );

                    if (!$transaction) {
                        throw new \Exception('Falha ao processar a transação da carteira.');
                    }

                    // Atualizar o pedido com as informações do pagamento
                    $paymentId = 'WALLET-' . $transaction->id;
                    $order->payment_id = $paymentId;
                    $order->status = Order::STATUS_PROCESSING;
                    $order->save();

                    // Registrar sucesso no log
                    logger()->info('Pagamento com carteira processado com sucesso', [
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'transaction_id' => $transaction->id,
                        'amount' => $total
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();

                    logger()->error('Erro ao processar pagamento com carteira', [
                        'user_id' => $user->id,
                        'order_id' => $order->id ?? null,
                        'amount' => $total ?? null,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    $this->dispatch('notify', [
                        'message' => 'Erro ao processar o pagamento com a carteira: ' . $e->getMessage(),
                        'type' => 'error'
                    ]);

                    // Notificar o frontend para reativar o botão
                    $this->dispatch('checkoutFailed');
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

            logger()->error('Erro ao processar o pedido', [
                'user_id' => Auth::id(),
                'payment_method' => $this->paymentMethod,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            $errorMessage = 'Erro ao processar o pedido: ' . $e->getMessage();

            // Simplificar a mensagem de erro para o usuário
            if (str_contains($e->getMessage(), 'token')) {
                $errorMessage = 'Erro ao processar o cartão de crédito. Por favor, verifique os dados do cartão e tente novamente.';
            } elseif (str_contains($e->getMessage(), 'wallet') || str_contains($e->getMessage(), 'saldo')) {
                $errorMessage = 'Erro ao processar o pagamento com a carteira. Por favor, verifique seu saldo e tente novamente.';
            }

            $this->dispatch('notify', [
                'message' => $errorMessage,
                'type' => 'error'
            ]);

            // Notificar o frontend para reativar o botão
            $this->dispatch('checkoutFailed');

            return;
        }
    }

    public function updatedPaymentMethod($value)
    {
        // Sempre atualizar o saldo da carteira quando o método de pagamento for alterado
        if ($value === 'wallet') {
            try {
                // Recarregar o usuário e a carteira para garantir dados atualizados
                $user = Auth::user();
                $user->refresh();

                // Obter a carteira atualizada
                $wallet = $user->wallet()->first();

                if ($wallet) {
                    // Forçar a atualização do saldo da carteira
                    $wallet->refresh();
                    $this->walletBalance = $wallet->balance;

                    logger()->info('Wallet balance updated in checkout', [
                        'user_id' => $user->id,
                        'wallet_id' => $wallet->id,
                        'balance' => $wallet->balance
                    ]);
                } else {
                    // Criar carteira se não existir
                    $wallet = \App\Models\Wallet::create([
                        'user_id' => $user->id,
                        'balance' => 0,
                        'active' => true
                    ]);
                    $this->walletBalance = 0;

                    logger()->info('New wallet created in checkout', [
                        'user_id' => $user->id,
                        'wallet_id' => $wallet->id
                    ]);
                }
            } catch (\Exception $e) {
                logger()->error('Error updating wallet balance in checkout', [
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Emitir evento para o JavaScript saber que o método de pagamento mudou
        $this->dispatch('paymentMethodChanged', $value);

        // Log para depuração
        logger()->info('Método de pagamento alterado', [
            'user_id' => Auth::id(),
            'method' => $value,
            'wallet_balance' => $this->walletBalance
        ]);
    }

    /**
     * Refresh the wallet balance
     */
    public function refreshWalletBalance()
    {
        if (Auth::check()) {
            try {
                $user = Auth::user();
                $user->refresh();

                $wallet = $user->wallet()->first();
                if ($wallet) {
                    $wallet->refresh();
                    $this->walletBalance = $wallet->balance;

                    logger()->info('Wallet balance refreshed', [
                        'user_id' => $user->id,
                        'wallet_id' => $wallet->id,
                        'balance' => $wallet->balance
                    ]);

                    $this->dispatch('notify', [
                        'message' => 'Saldo da carteira atualizado.',
                        'type' => 'success'
                    ]);
                }
            } catch (\Exception $e) {
                logger()->error('Error refreshing wallet balance', [
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Set the Stripe token received from the frontend
     */
    #[Attributes\On('setStripeToken')]
    public function setStripeToken($data)
    {
        $this->stripeToken = $data['token'];
        logger()->info('Stripe token set', [
            'user_id' => Auth::id(),
            'token_received' => true,
            'token' => $data['token']
        ]);
    }

    /**
     * Listener alternativo para o evento setStripeToken
     * Isso garante compatibilidade com diferentes formas de envio do evento
     */
    public function getListeners()
    {
        return [
            'setStripeToken' => 'setStripeToken'
        ];
    }

    public function render()
    {
        return view('livewire.shop.checkout');
    }
}
