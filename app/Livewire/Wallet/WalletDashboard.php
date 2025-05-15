<?php

namespace App\Livewire\Wallet;

use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class WalletDashboard extends Component
{
    use WithPagination;

    public $wallet;
    public $filter = 'all';

    // Livewire 3 usa o método getListeners() em vez de $listeners
    protected function getListeners()
    {
        return [
            'walletUpdated' => 'refreshWallet',
            'walletBalanceUpdated' => 'refreshWallet'
        ];
    }

    public function mount()
    {
        $this->wallet = Auth::user()->wallet;
    }

    public function render()
    {
        $query = WalletTransaction::where('user_id', Auth::id())
            ->with(['sourceUser']);

        if ($this->filter !== 'all') {
            $query->where('type', $this->filter);
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.wallet.dashboard', [
            'transactions' => $transactions,
        ]);
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function refreshWallet()
    {
        // Recarregar a carteira do usuário para obter o saldo atualizado
        $this->wallet = Auth::user()->wallet()->fresh();
    }
}
