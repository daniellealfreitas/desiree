<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
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
     * Carrinho ao qual este item pertence.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
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
