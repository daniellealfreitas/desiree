<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'phone',
    ];

    // Relacionamento: endereço pertence a um usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}