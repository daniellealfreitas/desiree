<?php

namespace App\Livewire\Wallet;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class AddFunds extends Component
{
    public $amount = 50.00;
    public $checkoutUrl = null;
    public $processing = false;

    public function render()
    {
        return view('livewire.wallet.add-funds');
    }

    public function createCheckoutSession()
    {
        $this->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $this->processing = true;

        try {
            Stripe::setApiKey(config('cashier.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'brl',
                        'product_data' => [
                            'name' => "Adicionar Fundos à Carteira",
                            'description' => "Adicionar R$ " . number_format($this->amount, 2, ',', '.') . " à sua carteira",
                        ],
                        'unit_amount' => (int)($this->amount * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('wallet.add-funds.success', ['amount' => $this->amount]),
                'cancel_url' => route('wallet.add-funds.cancel'),
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'user_id' => Auth::id(),
                    'type' => 'wallet_deposit',
                ],
            ]);

            $this->checkoutUrl = $session->url;
            $this->dispatch('openCheckout', url: $this->checkoutUrl);
        } catch (\Exception $e) {
            $this->addError('amount', 'Erro ao processar pagamento: ' . $e->getMessage());
            $this->processing = false;
        }
    }
}
