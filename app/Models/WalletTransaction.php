<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'user_id',
        'source_user_id',
        'type',
        'amount',
        'balance_after',
        'status',
        'reference_id',
        'reference_type',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * Get the wallet that owns the transaction.
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the source user for the transaction.
     */
    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source_user_id');
    }

    /**
     * Get the formatted amount with sign
     */
    public function getFormattedAmountAttribute()
    {
        $sign = $this->amount >= 0 ? '+' : '';
        return $sign . 'R$ ' . number_format(abs($this->amount), 2, ',', '.');
    }

    /**
     * Get the formatted balance
     */
    public function getFormattedBalanceAttribute()
    {
        return 'R$ ' . number_format($this->balance_after, 2, ',', '.');
    }

    /**
     * Get the transaction type label
     */
    public function getTypeTextAttribute()
    {
        $types = [
            'deposit' => 'Depósito',
            'withdrawal' => 'Saque',
            'transfer_in' => 'Transferência Recebida',
            'transfer_out' => 'Transferência Enviada',
            'purchase' => 'Compra',
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * Get the transaction status label
     */
    public function getStatusTextAttribute()
    {
        $statuses = [
            'completed' => 'Concluído',
            'pending' => 'Pendente',
            'failed' => 'Falhou',
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}
