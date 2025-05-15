<?php

namespace App\Livewire\Wallet;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransferFunds extends Component
{
    public $username = '';
    public $amount = 10.00;
    public $description = '';
    public $recipientUser = null;
    public $searchResults = [];
    public $showResults = false;

    public function render()
    {
        return view('livewire.wallet.transfer-funds');
    }

    public function searchUser()
    {
        if (strlen($this->username) >= 3) {
            $this->searchResults = User::where('username', 'like', '%' . $this->username . '%')
                ->orWhere('name', 'like', '%' . $this->username . '%')
                ->where('id', '!=', Auth::id())
                ->limit(5)
                ->get();
            $this->showResults = true;
        } else {
            $this->searchResults = [];
            $this->showResults = false;
        }
    }

    public function selectUser($username)
    {
        $this->username = $username;
        $this->recipientUser = User::where('username', $username)->first();
        $this->showResults = false;
    }

    public function transfer()
    {
        $this->validate([
            'username' => 'required|exists:users,username',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $sender = Auth::user();
        $recipient = User::where('username', $this->username)->first();

        // Check if user is trying to transfer to themselves
        if ($sender->id === $recipient->id) {
            $this->addError('username', 'Você não pode transferir para si mesmo.');
            return;
        }

        // Check if sender has enough funds
        if ($sender->wallet->balance < $this->amount) {
            $this->addError('amount', 'Saldo insuficiente para esta transferência.');
            return;
        }

        // Process transfer
        $result = $sender->wallet->transferTo($recipient, $this->amount, $this->description);

        if ($result) {
            // Create notification for recipient
            $recipient->notifications()->create([
                'sender_id' => $sender->id,
                'type' => 'wallet_transfer',
                'message' => "{$sender->name} transferiu R$ " . number_format($this->amount, 2, ',', '.') . " para você.",
                'read' => false,
            ]);

            session()->flash('success', 'Transferência realizada com sucesso!');
            $this->reset(['username', 'amount', 'description', 'recipientUser']);
        } else {
            $this->addError('amount', 'Erro ao processar a transferência.');
        }
    }

    public function updated($property)
    {
        if ($property === 'username') {
            $this->searchUser();
        }
    }
}
