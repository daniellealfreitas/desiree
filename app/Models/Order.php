<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'discount',
        'status',
        'payment_method',
        'payment_id',
        'shipping_address',
        'shipping_cost',
        'notes',
        'coupon_id',
        'tracking_number'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'shipping_address' => 'array',
    ];

    /**
     * Status possíveis para um pedido.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    /**
     * Usuário que fez o pedido.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Itens do pedido.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Cupom aplicado ao pedido.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Retorna o total com desconto.
     */
    public function getTotalWithDiscount()
    {
        return max(0, $this->total - $this->discount);
    }

    /**
     * Retorna o total final (com desconto e frete).
     */
    public function getFinalTotal()
    {
        return $this->getTotalWithDiscount() + $this->shipping_cost;
    }

    /**
     * Verifica se o pedido pode ser cancelado.
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING
        ]);
    }

    /**
     * Verifica se o pedido pode ser reembolsado.
     */
    public function canBeRefunded()
    {
        return in_array($this->status, [
            self::STATUS_COMPLETED,
            self::STATUS_SHIPPED,
            self::STATUS_DELIVERED
        ]);
    }

    /**
     * Escopo para pedidos pendentes.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Escopo para pedidos em processamento.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    /**
     * Escopo para pedidos completados.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Escopo para pedidos enviados.
     */
    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    /**
     * Escopo para pedidos entregues.
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    /**
     * Escopo para pedidos cancelados.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Escopo para pedidos reembolsados.
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', self::STATUS_REFUNDED);
    }
}
