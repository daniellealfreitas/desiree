<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'options'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'options' => 'array',
    ];

    /**
     * Pedido ao qual este item pertence.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Produto associado a este item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Retorna o subtotal deste item.
     */
    public function getSubtotal()
    {
        return $this->price * $this->quantity;
    }
}
