<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'description',
        'active',
        'expires_at',
        'usage_limit',
        'used',
        'min_purchase',
        'max_discount'
    ];

    protected $casts = [
        'active' => 'boolean',
        'expires_at' => 'datetime',
        'usage_limit' => 'integer',
        'used' => 'integer',
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    /**
     * Pedidos que usaram este cupom.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Carrinhos que estão usando este cupom.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Verifica se o cupom é válido.
     */
    public function isValid($cartTotal = null)
    {
        // Verificar se está ativo
        if (!$this->active) {
            return false;
        }

        // Verificar se expirou
        if ($this->expires_at && $this->expires_at < now()) {
            return false;
        }

        // Verificar se atingiu o limite de uso
        if ($this->usage_limit && $this->used >= $this->usage_limit) {
            return false;
        }

        // Verificar valor mínimo de compra
        if ($cartTotal !== null && $this->min_purchase > 0 && $cartTotal < $this->min_purchase) {
            return false;
        }

        return true;
    }

    /**
     * Incrementa o contador de uso do cupom.
     */
    public function incrementUsage()
    {
        $this->used++;
        $this->save();
    }

    /**
     * Calcula o valor do desconto.
     */
    public function calculateDiscount($total)
    {
        if ($this->type === 'percentage') {
            $discount = ($total * $this->value) / 100;

            // Aplicar limite máximo de desconto, se definido
            if ($this->max_discount > 0) {
                $discount = min($discount, $this->max_discount);
            }

            return $discount;
        }

        // Desconto fixo (não pode ser maior que o total)
        return min($this->value, $total);
    }
}