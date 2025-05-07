<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Os atributos que são atribuíveis em massa.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'featured',
        'status'
    ];

    /**
     * Os atributos que devem ser convertidos.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'featured' => 'boolean',
        'stock' => 'integer',
    ];

    /**
     * Usuários que adicionaram o produto na wishlist (many-to-many).
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    /**
     * Reviews do produto.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Categoria do produto.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Pedidos que contêm este produto.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Verifica se o produto está em estoque.
     */
    public function inStock()
    {
        return $this->stock > 0;
    }

    /**
     * Escopo para produtos ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Escopo para produtos em destaque.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
