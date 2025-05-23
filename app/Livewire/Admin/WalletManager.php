<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class WalletManager extends Component
{
    use WithPagination;

    // Filtros e paginação
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $filterByBalance = '';

    // Campos para adicionar fundos
    public $selectedUserId;
    public $selectedUserName;
    public $amount = 0;
    public $description = '';
    public $transactionType = 'admin_deposit';

    // Campos para visualizar transações
    public $viewingTransactions = false;
    public $currentUserId;
    public $currentUserName;
    public $transactionFilter = 'all';

    // Controle de modais
    public $activeModal = null;
    public $modalData = [];

    // Inicialização do componente
    public function mount()
    {
        $this->activeModal = null;
        $this->modalData = [];
    }

    // Livewire 3 listeners
    protected function getListeners()
    {
        return [
            'transactionFilter' => 'setTransactionFilter',
            'openModal' => 'openModal',
            'closeModal' => 'closeModal',
            'walletUpdated' => 'handleWalletUpdated',
            'walletBalanceUpdated' => 'handleWalletUpdated'
        ];
    }

    /**
     * Manipula o evento walletUpdated para forçar a atualização do componente
     */
    public function handleWalletUpdated()
    {
        logger()->info('WalletManager::handleWalletUpdated - Evento recebido');

        // Limpar o cache do Eloquent para garantir dados atualizados
        \Illuminate\Database\Eloquent\Model::clearBootedModels();

        // Limpar o cache de consultas
        DB::connection()->disableQueryLog();
        DB::connection()->flushQueryLog();

        // Forçar a atualização dos dados
        if ($this->selectedUserId) {
            $user = User::withoutGlobalScopes()->with(['wallet' => function($q) {
                $q->withoutGlobalScopes();
            }])->find($this->selectedUserId);

            if ($user && $user->wallet) {
                // Forçar a recarga da carteira
                $user->wallet->refresh();

                logger()->info('WalletManager::handleWalletUpdated - Saldo atualizado:', [
                    'user_id' => $user->id,
                    'wallet_id' => $user->wallet->id,
                    'balance' => $user->wallet->balance,
                ]);
            }
        }

        // Forçar a renderização do componente
        $this->render();
    }

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'description' => 'required|string|max:255',
        'transactionType' => 'required|string',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterByBalance()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Abre uma modal específica
     *
     * @param string $modal Nome da modal a ser aberta
     * @param array $data Dados adicionais para a modal
     */
    public function openModal($modal, $data = [])
    {
        $this->activeModal = $modal;
        $this->modalData = $data;

        if ($modal === 'addFunds') {
            $this->selectedUserId = $data['userId'] ?? null;
            $this->selectedUserName = $data['userName'] ?? '';
            $this->amount = 0;
            $this->description = '';
            $this->transactionType = 'admin_deposit';

            // Recarregar o usuário e a carteira para garantir dados atualizados
            if ($this->selectedUserId) {
                $user = User::with(['wallet' => function($q) {
                    $q->withoutGlobalScopes();
                }])->find($this->selectedUserId);

                if ($user && $user->wallet) {
                    // Forçar a recarga da carteira
                    $user->wallet->refresh();
                }
            }
        } elseif ($modal === 'transactions') {
            $this->currentUserId = $data['userId'] ?? null;
            $this->currentUserName = $data['userName'] ?? '';
            $this->transactionFilter = 'all';

            // Recarregar o usuário e as transações para garantir dados atualizados
            if ($this->currentUserId) {
                $user = User::with(['wallet' => function($q) {
                    $q->withoutGlobalScopes();
                }])->find($this->currentUserId);

                if ($user && $user->wallet) {
                    // Forçar a recarga da carteira
                    $user->wallet->refresh();
                }
            }
        }
    }

    /**
     * Fecha a modal ativa
     */
    public function closeModal()
    {
        $this->activeModal = null;
        $this->modalData = [];
    }

    /**
     * Método legado para compatibilidade
     */
    public function openAddFundsModal($userId, $userName)
    {
        $this->openModal('addFunds', [
            'userId' => $userId,
            'userName' => $userName
        ]);
    }

/**
 * Recarrega o saldo diretamente do banco de dados
 *
 * @param int $userId ID do usuário
 * @return float Saldo atualizado
 */
private function reloadWalletBalance($userId)
{
    // Consultar diretamente o banco de dados para obter o saldo mais recente
    $wallet = DB::table('wallets')->where('user_id', $userId)->first();

    if ($wallet) {
        logger()->info('Saldo recarregado diretamente do banco de dados:', [
            'user_id' => $userId,
            'wallet_id' => $wallet->id,
            'balance' => $wallet->balance,
        ]);

        return $wallet->balance;
    }

    return 0;
}

public function addFunds()
{
    $this->validate();

    try {
        // Converter o valor para float para garantir que seja tratado corretamente
        $amount = (float) $this->amount;

        if ($amount <= 0) {
            throw new \Exception('O valor deve ser maior que zero.');
        }

        $user = User::findOrFail($this->selectedUserId);
        $wallet = $user->wallet;

        if (!$wallet) {
            throw new \Exception('Usuário não possui uma carteira associada.');
        }

        logger()->info('Adicionando fundos:', [
            'user_id' => $this->selectedUserId,
            'wallet_id' => $wallet->id,
            'amount' => $amount,
            'type' => $this->transactionType,
            'description' => $this->description,
            'admin_id' => auth()->id(),
        ]);

        // Registrar o saldo antes da operação
        logger()->info('Saldo antes da operação:', [
            'user_id' => $this->selectedUserId,
            'wallet_id' => $wallet->id,
            'balance_before' => $wallet->balance,
        ]);

        DB::beginTransaction();

        try {
            // Usar o método addFunds do modelo Wallet para garantir consistência
            $transaction = $wallet->addFunds(
                $amount,
                $this->transactionType,
                $this->description,
                null,
                'admin_action',
                auth()->id()
            );

            if (!$transaction) {
                throw new \Exception('Falha ao adicionar fundos à carteira.');
            }

            // Recarregar o wallet para garantir que temos os dados mais recentes
            $wallet->refresh();

            // Registrar o saldo após a operação e a transação criada
            logger()->info('Saldo após a operação:', [
                'user_id' => $this->selectedUserId,
                'wallet_id' => $wallet->id,
                'balance_after' => $wallet->balance,
                'transaction_id' => $transaction->id,
                'transaction_amount' => $transaction->amount,
                'transaction_balance_after' => $transaction->balance_after,
            ]);

            // Verificar o saldo diretamente no banco de dados
            $directBalance = $this->reloadWalletBalance($this->selectedUserId);

            logger()->info('Comparação de saldos:', [
                'user_id' => $this->selectedUserId,
                'wallet_id' => $wallet->id,
                'model_balance' => $wallet->balance,
                'direct_db_balance' => $directBalance,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $this->closeModal();

        $this->dispatch('notify', [
            'message' => "R$ " . number_format($amount, 2, ',', '.') . " adicionado à carteira de {$this->selectedUserName} com sucesso!",
            'type' => 'success'
        ]);

        // Recarregar o usuário e a carteira para garantir dados atualizados
        $user = User::withoutGlobalScopes()->with(['wallet' => function($q) {
            $q->withoutGlobalScopes();
        }])->find($this->selectedUserId);

        if ($user && $user->wallet) {
            // Forçar a recarga da carteira
            $user->wallet->refresh();

            logger()->info('WalletManager::addFunds - Saldo final após operação completa:', [
                'user_id' => $user->id,
                'wallet_id' => $user->wallet->id,
                'balance' => $user->wallet->balance,
            ]);
        }

        // Forçar a atualização da interface
        $this->dispatch('walletUpdated');
        $this->dispatch('walletBalanceUpdated');

        $this->reset(['amount', 'description', 'transactionType']);

        // Limpar o cache do Eloquent para garantir dados atualizados
        \Illuminate\Database\Eloquent\Model::clearBootedModels();

        // Forçar a renderização para atualizar a lista de usuários
        $this->render();

    } catch (\Exception $e) {
        logger()->error('Erro ao adicionar fundos: ' . $e->getMessage());

        $this->dispatch('notify', [
            'message' => 'Erro ao adicionar fundos: ' . $e->getMessage(),
            'type' => 'error'
        ]);
    }
}


    public function subtractFunds()
    {
        $this->validate();

        try {
            // Converter o valor para float para garantir que seja tratado corretamente
            $amount = (float) $this->amount;

            if ($amount <= 0) {
                throw new \Exception('O valor deve ser maior que zero.');
            }

            $user = User::findOrFail($this->selectedUserId);
            $wallet = $user->wallet;

            // Verificar se há saldo suficiente
            if ($wallet->balance < $amount) {
                throw new \Exception('Saldo insuficiente na carteira do usuário.');
            }

            // Registrar o saldo antes da operação
            logger()->info('Saldo antes da subtração:', [
                'user_id' => $this->selectedUserId,
                'wallet_id' => $wallet->id,
                'balance_before' => $wallet->balance,
                'amount_to_subtract' => $amount,
            ]);

            DB::beginTransaction();

            try {
                // Usar o método subtractFunds do modelo Wallet para garantir consistência
                $transaction = $wallet->subtractFunds(
                    $amount,
                    'admin_withdrawal',
                    $this->description,
                    null,
                    'admin_action',
                    auth()->id()
                );

                if (!$transaction) {
                    throw new \Exception('Falha ao remover fundos da carteira.');
                }

                // Recarregar o wallet para garantir que temos os dados mais recentes
                $wallet->refresh();

                // Registrar o saldo após a operação e a transação criada
                logger()->info('Saldo após a subtração:', [
                    'user_id' => $this->selectedUserId,
                    'wallet_id' => $wallet->id,
                    'balance_after' => $wallet->balance,
                    'transaction_id' => $transaction->id,
                    'transaction_amount' => $transaction->amount,
                    'transaction_balance_after' => $transaction->balance_after,
                ]);

                // Verificar o saldo diretamente no banco de dados
                $directBalance = $this->reloadWalletBalance($this->selectedUserId);

                logger()->info('Comparação de saldos após subtração:', [
                    'user_id' => $this->selectedUserId,
                    'wallet_id' => $wallet->id,
                    'model_balance' => $wallet->balance,
                    'direct_db_balance' => $directBalance,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            // Atualizar a interface
            $this->closeModal();
            $this->dispatch('notify', [
                'message' => "R$ " . number_format($amount, 2, ',', '.') . " removido da carteira de {$this->selectedUserName} com sucesso!",
                'type' => 'success'
            ]);

            // Recarregar o usuário e a carteira para garantir dados atualizados
            $user = User::withoutGlobalScopes()->with(['wallet' => function($q) {
                $q->withoutGlobalScopes();
            }])->find($this->selectedUserId);

            if ($user && $user->wallet) {
                // Forçar a recarga da carteira
                $user->wallet->refresh();

                logger()->info('WalletManager::subtractFunds - Saldo final após operação completa:', [
                    'user_id' => $user->id,
                    'wallet_id' => $user->wallet->id,
                    'balance' => $user->wallet->balance,
                ]);
            }

            // Forçar a atualização da lista de usuários e outros componentes relacionados
            $this->dispatch('walletUpdated');

            // Atualizar o componente de saldo da carteira no header, se existir
            $this->dispatch('walletBalanceUpdated');

            // Limpar o cache do Eloquent para garantir dados atualizados
            \Illuminate\Database\Eloquent\Model::clearBootedModels();

            // Forçar a atualização completa do componente
            $this->reset(['amount', 'description', 'transactionType']);
            $this->render();
        } catch (\Exception $e) {
            logger()->error('Erro ao remover fundos: ' . $e->getMessage());

            $this->dispatch('notify', [
                'message' => 'Erro ao remover fundos: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Método legado para compatibilidade
     */
    public function viewTransactions($userId, $userName)
    {
        $this->openModal('transactions', [
            'userId' => $userId,
            'userName' => $userName
        ]);
    }

    /**
     * Cria uma nova carteira para o usuário
     */
    public function createWallet($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Verificar se o usuário já tem uma carteira
            if ($user->wallet()->exists()) {
                $this->dispatch('notify', [
                    'message' => "O usuário já possui uma carteira!",
                    'type' => 'info'
                ]);
                return;
            }

            // Criar uma nova carteira
            $wallet = $user->wallet()->create([
                'balance' => 0.00,
                'active' => true,
            ]);

            $this->dispatch('notify', [
                'message' => "Carteira criada com sucesso para {$user->name}!",
                'type' => 'success'
            ]);

            // Forçar a atualização da lista de usuários e outros componentes relacionados
            $this->dispatch('walletUpdated');

            // Atualizar o componente de saldo da carteira no header, se existir
            $this->dispatch('walletBalanceUpdated');
        } catch (\Exception $e) {
            logger()->error('Erro ao criar carteira: ' . $e->getMessage());

            $this->dispatch('notify', [
                'message' => 'Erro ao criar carteira: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function toggleWalletStatus($walletId)
    {
        try {
            $wallet = Wallet::findOrFail($walletId);
            $wallet->active = !$wallet->active;
            $wallet->save();

            $status = $wallet->active ? 'ativada' : 'desativada';
            $this->dispatch('notify', [
                'message' => "Carteira {$status} com sucesso!",
                'type' => 'success'
            ]);

            // Forçar a atualização da lista de usuários e outros componentes relacionados
            $this->dispatch('walletUpdated');

            // Atualizar o componente de saldo da carteira no header, se existir
            $this->dispatch('walletBalanceUpdated');
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao alterar status da carteira: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function getUserTransactions()
    {
        $query = WalletTransaction::where('user_id', $this->currentUserId)
            ->with(['sourceUser']);

        if ($this->transactionFilter !== 'all') {
            $query->where('type', $this->transactionFilter);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    // Método para atualizar a lista de transações quando o filtro é alterado
    public function updatedTransactionFilter()
    {
        // Não é necessário fazer nada aqui, pois o método getUserTransactions
        // já usa o valor atual de $this->transactionFilter
    }

    // Método para definir o filtro de transações via evento
    public function setTransactionFilter($filter)
    {
        $this->transactionFilter = $filter;
    }

    public function render()
    {
        // Limpar o cache do Eloquent para garantir dados atualizados
        \Illuminate\Database\Eloquent\Model::clearBootedModels();

        // Desativar o cache de consultas para garantir dados atualizados
        DB::connection()->disableQueryLog();
        DB::connection()->flushQueryLog();

        // Garantir que estamos sempre buscando dados atualizados do banco de dados
        $query = User::query()
            ->withoutGlobalScopes()
            ->with(['wallet' => function($q) {
                $q->withoutGlobalScopes(); // Garantir que não há escopos globais interferindo
            }]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterByBalance === 'positive') {
            $query->whereHas('wallet', function ($q) {
                $q->where('balance', '>', 0);
            });
        } elseif ($this->filterByBalance === 'zero') {
            $query->whereHas('wallet', function ($q) {
                $q->where('balance', 0);
            });
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        // Desativar o cache para garantir dados atualizados
        $users = $query->paginate($this->perPage);

        // Forçar a recarga dos relacionamentos com dados frescos
        $users->load(['wallet' => function($q) {
            $q->withoutGlobalScopes();
        }]);

        // Log para verificar os saldos dos usuários
        foreach ($users as $user) {
            logger()->info('Render - Saldo do usuário:', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'wallet_id' => $user->wallet->id ?? 'N/A',
                'balance' => $user->wallet->balance ?? 'N/A',
            ]);
        }

        return view('livewire.admin.wallet-manager', [
            'users' => $users,
            'transactions' => $this->activeModal === 'transactions' ? $this->getUserTransactions() : null,
        ])->layout('layouts.app', ['title' => 'Gerenciar Carteiras']);
    }
}
