<?php

namespace App\Livewire\Wallet;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WalletBalance extends Component
{
    public $balance;

    // Livewire 3 usa o método getListeners() em vez de $listeners
    protected function getListeners()
    {
        return [
            'walletUpdated' => 'refreshBalance',
            'walletBalanceUpdated' => 'refreshBalance'
        ];
    }

    public function mount()
    {
        $this->refreshBalance();
    }

    public function refreshBalance()
    {
        $this->balance = Auth::user()->wallet->balance;
    }

    public function render()
    {
        return view('livewire.wallet.wallet-balance');
    }
}
