<?php

namespace App\Livewire\Wallet;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WithdrawFunds extends Component
{
    public $amount = 50.00;
    public $pixKey = '';
    public $pixKeyType = 'cpf';
    public $processing = false;

    public function render()
    {
        return view('livewire.wallet.withdraw-funds');
    }

    public function withdraw()
    {
        $this->validate([
            'amount' => 'required|numeric|min:50',
            'pixKey' => 'required|string',
            'pixKeyType' => 'required|in:cpf,cnpj,email,phone,random',
        ]);

        $user = Auth::user();

        // Check if user has enough funds
        if ($user->wallet->balance < $this->amount) {
            $this->addError('amount', 'Saldo insuficiente para este saque.');
            return;
        }

        $this->processing = true;

        // Process withdrawal (create pending transaction)
        $transaction = $user->wallet->subtractFunds(
            $this->amount,
            'withdrawal',
            'Saque via PIX: ' . $this->pixKeyType . ' - ' . $this->pixKey
        );

        if ($transaction) {
            // In a real application, you would process the PIX transfer here
            // For now, we'll just create a pending transaction

            session()->flash('success', 'Solicitação de saque enviada com sucesso! Seu saque será processado em até 24 horas.');
            $this->reset(['amount', 'pixKey', 'pixKeyType']);
        } else {
            $this->addError('amount', 'Erro ao processar o saque.');
        }

        $this->processing = false;
    }
}
