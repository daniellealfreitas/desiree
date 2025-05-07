<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'total',
        'coupon_id',
        'discount'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    /**
     * Usuário dono do carrinho.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Itens no carrinho.
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Cupom aplicado ao carrinho.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Calcula o total do carrinho.
     */
    public function calculateTotal()
    {
        $total = $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $this->total = $total;
        $this->save();

        return $total;
    }

    /**
     * Aplica um cupom ao carrinho.
     */
    public function applyCoupon(Coupon $coupon)
    {
        $this->coupon_id = $coupon->id;
        
        if ($coupon->type === 'percentage') {
            $this->discount = ($this->total * $coupon->value) / 100;
        } else {
            $this->discount = $coupon->value;
        }

        $this->save();
    }

    /**
     * Remove um cupom do carrinho.
     */
    public function removeCoupon()
    {
        $this->coupon_id = null;
        $this->discount = 0;
        $this->save();
    }

    /**
     * Retorna o total com desconto.
     */
    public function getTotalWithDiscount()
    {
        return max(0, $this->total - $this->discount);
    }

    /**
     * Limpa o carrinho.
     */
    public function clear()
    {
        $this->items()->delete();
        $this->total = 0;
        $this->discount = 0;
        $this->coupon_id = null;
        $this->save();
    }
}
