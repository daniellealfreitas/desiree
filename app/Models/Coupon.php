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
        'usage_limit',
        'used',
        'expires_at',
        'active',
    ];

    protected $dates = ['expires_at'];

    // Relacionamento: um cupom pode estar em várias orders (opcional - depende do model Order)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}