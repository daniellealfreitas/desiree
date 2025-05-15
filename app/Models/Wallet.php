<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'active' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wallet) {
            logger()->info('Wallet::creating - Nova carteira sendo criada', [
                'user_id' => $wallet->user_id,
                'initial_balance' => $wallet->balance,
            ]);
        });

        static::updating(function ($wallet) {
            if ($wallet->isDirty('balance')) {
                logger()->info('Wallet::updating - Saldo sendo atualizado', [
                    'wallet_id' => $wallet->id,
                    'user_id' => $wallet->user_id,
                    'old_balance' => $wallet->getOriginal('balance'),
                    'new_balance' => $wallet->balance,
                    'difference' => $wallet->balance - $wallet->getOriginal('balance')
                ]);
            }
        });
    }

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for the wallet.
     */
    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Add funds to the wallet
     *
     * @param float $amount
     * @param string $type
     * @param string|null $description
     * @param string|null $referenceId
     * @param string|null $referenceType
     * @param int|null $sourceUserId
     * @return WalletTransaction
     */
    /**
     * Atualiza diretamente o saldo no banco de dados
     *
     * @param float $newBalance Novo saldo
     * @return bool
     */
    public function updateBalanceDirectly(float $newBalance)
    {
        // Verificar se o valor é válido
        if ($newBalance < 0) {
            logger()->error('Wallet::updateBalanceDirectly - Tentativa de definir saldo negativo', [
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'old_balance' => $this->balance,
                'new_balance' => $newBalance
            ]);
            return false;
        }

        logger()->info('Wallet::updateBalanceDirectly - Atualizando saldo diretamente', [
            'wallet_id' => $this->id,
            'user_id' => $this->user_id,
            'old_balance' => $this->balance,
            'new_balance' => $newBalance,
            'difference' => $newBalance - $this->balance
        ]);

        try {
            $result = DB::table('wallets')
                ->where('id', $this->id)
                ->update(['balance' => $newBalance]);

            if ($result) {
                logger()->info('Wallet::updateBalanceDirectly - Saldo atualizado com sucesso', [
                    'wallet_id' => $this->id,
                    'new_balance' => $newBalance
                ]);
            } else {
                logger()->error('Wallet::updateBalanceDirectly - Falha ao atualizar saldo', [
                    'wallet_id' => $this->id,
                    'user_id' => $this->user_id
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            logger()->error('Wallet::updateBalanceDirectly - Exceção ao atualizar saldo', [
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function addFunds(
        float $amount,
        string $type = 'deposit',
        ?string $description = null,
        ?string $referenceId = null,
        ?string $referenceType = null,
        ?int $sourceUserId = null
    ) {
        return DB::transaction(function () use ($amount, $type, $description, $referenceId, $referenceType, $sourceUserId) {
            // Log do saldo antes da atualização
            logger()->info('Wallet::addFunds - Saldo antes:', [
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'balance_before' => $this->balance,
                'amount_to_add' => $amount
            ]);

            // Update wallet balance
            $oldBalance = $this->balance;
            $newBalance = $oldBalance + $amount;

            // Atualizar diretamente no banco de dados
            $updated = $this->updateBalanceDirectly($newBalance);

            if (!$updated) {
                logger()->error('Wallet::addFunds - Falha ao atualizar saldo diretamente no banco de dados', [
                    'wallet_id' => $this->id,
                    'user_id' => $this->user_id,
                    'old_balance' => $oldBalance,
                    'new_balance' => $newBalance,
                ]);
            }

            // Atualizar o modelo
            $this->balance = $newBalance;
            $this->save();

            // Recarregar o modelo para garantir que temos os dados mais recentes
            $this->refresh();

            // Log do saldo após a atualização
            logger()->info('Wallet::addFunds - Saldo após:', [
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'old_balance' => $oldBalance,
                'new_balance' => $this->balance,
                'amount_added' => $amount
            ]);

            // Create transaction record
            $transaction = WalletTransaction::create([
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'source_user_id' => $sourceUserId,
                'type' => $type,
                'amount' => $amount,
                'balance_after' => $this->balance,
                'status' => 'completed',
                'reference_id' => $referenceId,
                'reference_type' => $referenceType,
                'description' => $description,
            ]);

            // Log da transação criada
            logger()->info('Wallet::addFunds - Transação criada:', [
                'transaction_id' => $transaction->id,
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'amount' => $transaction->amount,
                'balance_after' => $transaction->balance_after
            ]);

            return $transaction;
        });
    }

    /**
     * Subtract funds from the wallet
     *
     * @param float $amount
     * @param string $type
     * @param string|null $description
     * @param string|null $referenceId
     * @param string|null $referenceType
     * @param int|null $sourceUserId
     * @return WalletTransaction|bool
     */
    public function subtractFunds(
        float $amount,
        string $type = 'withdrawal',
        ?string $description = null,
        ?string $referenceId = null,
        ?string $referenceType = null,
        ?int $sourceUserId = null
    ) {
        // Verificar se o valor é positivo
        if ($amount <= 0) {
            logger()->error('Wallet::subtractFunds - Tentativa de subtrair valor não positivo', [
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'amount' => $amount
            ]);
            return false;
        }

        // Verificar se há saldo suficiente
        if ($this->balance < $amount) {
            logger()->error('Wallet::subtractFunds - Saldo insuficiente', [
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'balance' => $this->balance,
                'amount' => $amount
            ]);
            return false;
        }

        return DB::transaction(function () use ($amount, $type, $description, $referenceId, $referenceType, $sourceUserId) {
            // Log do saldo antes da atualização
            logger()->info('Wallet::subtractFunds - Saldo antes:', [
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'balance_before' => $this->balance,
                'amount_to_subtract' => $amount
            ]);

            // Update wallet balance
            $oldBalance = $this->balance;
            $newBalance = $oldBalance - $amount;

            // Atualizar diretamente no banco de dados
            $updated = $this->updateBalanceDirectly($newBalance);

            if (!$updated) {
                logger()->error('Wallet::subtractFunds - Falha ao atualizar saldo diretamente no banco de dados', [
                    'wallet_id' => $this->id,
                    'user_id' => $this->user_id,
                    'old_balance' => $oldBalance,
                    'new_balance' => $newBalance,
                ]);
            }

            // Atualizar o modelo
            $this->balance = $newBalance;
            $this->save();

            // Recarregar o modelo para garantir que temos os dados mais recentes
            $this->refresh();

            // Log do saldo após a atualização
            logger()->info('Wallet::subtractFunds - Saldo após:', [
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'old_balance' => $oldBalance,
                'new_balance' => $this->balance,
                'amount_subtracted' => $amount
            ]);

            // Create transaction record
            $transaction = WalletTransaction::create([
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'source_user_id' => $sourceUserId,
                'type' => $type,
                'amount' => -$amount, // Negative amount for outgoing transactions
                'balance_after' => $this->balance,
                'status' => 'completed',
                'reference_id' => $referenceId,
                'reference_type' => $referenceType,
                'description' => $description,
            ]);

            // Log da transação criada
            logger()->info('Wallet::subtractFunds - Transação criada:', [
                'transaction_id' => $transaction->id,
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'amount' => $transaction->amount,
                'balance_after' => $transaction->balance_after
            ]);

            return $transaction;
        });
    }

    /**
     * Transfer funds to another user's wallet
     *
     * @param User|int $recipient
     * @param float $amount
     * @param string|null $description
     * @return array|bool
     */
    public function transferTo($recipient, float $amount, ?string $description = null)
    {
        if ($this->balance < $amount) {
            return false;
        }

        $recipientId = $recipient instanceof User ? $recipient->id : $recipient;
        $recipientWallet = Wallet::where('user_id', $recipientId)->first();

        if (!$recipientWallet) {
            return false;
        }

        return DB::transaction(function () use ($recipientWallet, $amount, $description) {
            // Log dos saldos antes da transferência
            logger()->info('Wallet::transferTo - Saldos antes:', [
                'sender_wallet_id' => $this->id,
                'sender_user_id' => $this->user_id,
                'sender_balance' => $this->balance,
                'recipient_wallet_id' => $recipientWallet->id,
                'recipient_user_id' => $recipientWallet->user_id,
                'recipient_balance' => $recipientWallet->balance,
                'amount' => $amount
            ]);

            // Atualizar diretamente os saldos no banco de dados
            $senderNewBalance = $this->balance - $amount;
            $recipientNewBalance = $recipientWallet->balance + $amount;

            $senderUpdated = $this->updateBalanceDirectly($senderNewBalance);
            $recipientUpdated = $recipientWallet->updateBalanceDirectly($recipientNewBalance);

            if (!$senderUpdated || !$recipientUpdated) {
                logger()->error('Wallet::transferTo - Falha ao atualizar saldos diretamente no banco de dados', [
                    'sender_updated' => $senderUpdated,
                    'recipient_updated' => $recipientUpdated,
                    'sender_wallet_id' => $this->id,
                    'recipient_wallet_id' => $recipientWallet->id,
                ]);
            }

            // Atualizar os modelos
            $this->balance = $senderNewBalance;
            $this->save();

            $recipientWallet->balance = $recipientNewBalance;
            $recipientWallet->save();

            // Recarregar os modelos
            $this->refresh();
            $recipientWallet->refresh();

            // Log dos saldos após a atualização direta
            logger()->info('Wallet::transferTo - Saldos após atualização direta:', [
                'sender_wallet_id' => $this->id,
                'sender_user_id' => $this->user_id,
                'sender_balance' => $this->balance,
                'recipient_wallet_id' => $recipientWallet->id,
                'recipient_user_id' => $recipientWallet->user_id,
                'recipient_balance' => $recipientWallet->balance,
            ]);

            // Criar as transações
            $senderTransaction = WalletTransaction::create([
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'source_user_id' => $recipientWallet->user_id,
                'type' => 'transfer_out',
                'amount' => -$amount,
                'balance_after' => $this->balance,
                'status' => 'completed',
                'description' => $description,
            ]);

            $recipientTransaction = WalletTransaction::create([
                'wallet_id' => $recipientWallet->id,
                'user_id' => $recipientWallet->user_id,
                'source_user_id' => $this->user_id,
                'type' => 'transfer_in',
                'amount' => $amount,
                'balance_after' => $recipientWallet->balance,
                'status' => 'completed',
                'description' => $description,
            ]);

            // Log das transações criadas
            logger()->info('Wallet::transferTo - Transações criadas:', [
                'sender_transaction_id' => $senderTransaction->id,
                'recipient_transaction_id' => $recipientTransaction->id,
                'sender_balance_after' => $senderTransaction->balance_after,
                'recipient_balance_after' => $recipientTransaction->balance_after,
            ]);

            return [
                'sender_transaction' => $senderTransaction,
                'recipient_transaction' => $recipientTransaction,
            ];
        });
    }
}
