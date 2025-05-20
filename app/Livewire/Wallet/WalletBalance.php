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
        $user = Auth::user();
        $wallet = $user->wallet;

        if ($wallet) {
            $this->balance = $wallet->balance;
        } else {
            $this->balance = 0;
        }
    }

    public function render()
    {
        return view('livewire.wallet.wallet-balance');
    }
}
