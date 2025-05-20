<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SendCharm extends Component
{
    public $userId;
    public $user;
    public $showModal = false;
    public $message = '';
    public $selectedCharm = null;
    public $walletBalance = 0.00;
    public $processing = false;
    public $success = false;

    // Define charm types with their prices and icons
    public $charms = [
        'flower' => [
            'name' => 'Flor',
            'price' => 10.00,
            'icon' => 'sparkles',
            'description' => 'Uma linda flor para alegrar o dia'
        ],
        'kiss' => [
            'name' => 'Beijo',
            'price' => 20.00,
            'icon' => 'heart',
            'description' => 'Um beijo especial'
        ],
        'drink' => [
            'name' => 'Drink',
            'price' => 30.00,
            'icon' => 'beaker',
            'description' => 'Um drink para refrescar'
        ],
        'ticket' => [
            'name' => 'Ingresso',
            'price' => 250.00,
            'icon' => 'star',
            'description' => 'Um convite especial'
        ]
    ];

    protected function getListeners()
    {
        return ['show-send-charm' => 'loadUser'];
    }

    protected $rules = [
        'selectedCharm' => 'required|string',
        'message' => 'nullable|string|max:255',
    ];

    public function loadUser($userId)
    {
        $this->userId = $userId;
        $this->user = User::find($userId);
        $this->showModal = true;
        $this->refreshWalletBalance();
        $this->selectedCharm = null;
        $this->message = '';
        $this->success = false;
    }

    /**
     * Refresh the wallet balance
     */
    public function refreshWalletBalance()
    {
        $user = Auth::user();
        if ($user) {
            // Recarregar o usuário para garantir dados atualizados
            $user->refresh();

            // Obter a carteira e recarregá-la para garantir o saldo mais recente
            $wallet = $user->wallet()->first();
            if ($wallet) {
                $wallet->refresh();
                $this->walletBalance = $wallet->balance;
            } else {
                // Caso não tenha carteira, criar uma nova
                $wallet = $user->wallet()->create([
                    'balance' => 0.00,
                    'active' => true,
                ]);
                $this->walletBalance = 0.00;
            }
        }
    }

    /**
     * Select a charm
     */
    public function selectCharm($charmType)
    {
        $this->selectedCharm = $charmType;
    }

    /**
     * Get the price of the selected charm
     */
    public function getSelectedCharmPrice()
    {
        if (!$this->selectedCharm) {
            return 0;
        }

        return $this->charms[$this->selectedCharm]['price'];
    }

    /**
     * Send the charm
     */
    public function sendCharm()
    {
        $this->validate();

        if (!$this->selectedCharm) {
            $this->addError('charm', 'Por favor, selecione um charm para enviar.');
            return;
        }

        $this->processing = true;

        try {
            $sender = Auth::user();
            $wallet = $sender->wallet;
            $charmPrice = $this->getSelectedCharmPrice();

            // Refresh wallet balance to ensure we have the latest data
            $wallet->refresh();
            $this->walletBalance = $wallet->balance;

            // Check if user has enough balance
            if ($wallet->balance < $charmPrice) {
                throw new \Exception('Saldo insuficiente na carteira. Adicione fundos para enviar este charm.');
            }

            DB::beginTransaction();

            // Create a completed payment record
            $payment = Payment::create([
                'user_id' => $this->userId,
                'sender_id' => Auth::id(),
                'amount' => $charmPrice,
                'message' => $this->message ?: null,
                'status' => 'completed',
                'payment_id' => 'CHARM-' . uniqid(),
                'payment_date' => now(),
                'payment_method' => 'wallet',
                'charm_type' => $this->selectedCharm,
            ]);

            // Process wallet transaction for sender (subtract funds)
            $transaction = $wallet->subtractFunds(
                $charmPrice,
                'charm_sent',
                $this->charms[$this->selectedCharm]['name'] . ' para ' . $this->user->name,
                $payment->id,
                Payment::class
            );

            if (!$transaction) {
                throw new \Exception('Falha ao processar a transação da carteira.');
            }

            // Process wallet transaction for receiver (add funds)
            $receiverWallet = $this->user->wallet;
            $receiverTransaction = $receiverWallet->addFunds(
                $charmPrice,
                'charm_received',
                $this->charms[$this->selectedCharm]['name'] . ' de ' . $sender->name,
                $payment->id,
                Payment::class
            );

            if (!$receiverTransaction) {
                throw new \Exception('Falha ao adicionar fundos à carteira do destinatário.');
            }

            // Create notification for recipient
            $this->user->notifications()->create([
                'sender_id' => $sender->id,
                'type' => 'charm_received',
                'message' => "{$sender->name} enviou um charm '{$this->charms[$this->selectedCharm]['name']}' para você.",
                'read' => false,
                'data' => json_encode([
                    'charm_type' => $this->selectedCharm,
                    'amount' => $charmPrice,
                    'message' => $this->message
                ])
            ]);

            DB::commit();

            // Set success message and state
            $this->success = true;
            session()->flash('success', 'Charm enviado com sucesso para ' . $this->user->name);

        } catch (\Exception $e) {
            DB::rollBack();

            $this->addError('payment', 'Erro ao processar envio: ' . $e->getMessage());
            $this->processing = false;

            // Log do erro para depuração
            \Log::error('Erro no processamento do envio de charm', [
                'error' => $e->getMessage(),
                'user_id' => $this->userId,
                'sender_id' => Auth::id(),
                'charm_type' => $this->selectedCharm
            ]);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['selectedCharm', 'message', 'processing', 'success']);
        $this->dispatch('modal-closed');
    }

    public function render()
    {
        return view('livewire.send-charm');
    }
}
