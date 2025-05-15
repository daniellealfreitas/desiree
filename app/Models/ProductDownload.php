<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'user_id',
        'product_id',
        'download_count',
        'last_download',
        'expires_at',
    ];

    protected $casts = [
        'download_count' => 'integer',
        'last_download' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Relacionamento com o item do pedido.
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Relacionamento com o usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o produto.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Verifica se o download ainda é válido.
     */
    public function isValid()
    {
        // Verificar se expirou
        if ($this->expires_at && now()->gt($this->expires_at)) {
            return false;
        }

        // Verificar se atingiu o limite de downloads
        $product = $this->product;
        if ($product->download_limit && $this->download_count >= $product->download_limit) {
            return false;
        }

        return true;
    }

    /**
     * Incrementa o contador de downloads.
     */
    public function incrementDownloadCount()
    {
        $this->download_count += 1;
        $this->last_download = now();
        $this->save();
    }
}
