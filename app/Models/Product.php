<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * Os atributos que são atribuíveis em massa.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock',
        'image',
        'category_id',
        'featured',
        'status',
        'sku',
        'weight',
        'dimensions',
        'options',
        'color',
        'sale_starts_at',
        'sale_ends_at'
    ];

    /**
     * Os atributos que devem ser convertidos.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'featured' => 'boolean',
        'stock' => 'integer',
        'dimensions' => 'array',
        'options' => 'array',
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
    ];

    /**
     * Boot do modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

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

    /**
     * Escopo para produtos em promoção.
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->where(function ($query) {
                $query->whereNull('sale_starts_at')
                    ->orWhere('sale_starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('sale_ends_at')
                    ->orWhere('sale_ends_at', '>=', now());
            });
    }

    /**
     * Verifica se o produto está em promoção.
     */
    public function isOnSale()
    {
        if (!$this->sale_price || $this->sale_price <= 0) {
            return false;
        }

        $now = now();

        if ($this->sale_starts_at && $this->sale_starts_at > $now) {
            return false;
        }

        if ($this->sale_ends_at && $this->sale_ends_at < $now) {
            return false;
        }

        return true;
    }

    /**
     * Retorna o preço atual do produto (considerando promoções).
     */
    public function getCurrentPrice()
    {
        return $this->isOnSale() ? $this->sale_price : $this->price;
    }

    /**
     * Imagens do produto.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Imagem principal do produto.
     */
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    /**
     * Retorna a URL da imagem principal ou a primeira imagem disponível.
     */
    public function getImageUrl()
    {
        // Se tiver uma imagem no campo image, retorna ela
        if ($this->image) {
            return $this->image;
        }

        // Senão, tenta pegar a imagem principal ou a primeira imagem
        $image = $this->mainImage ?? $this->images()->first();

        return $image ? $image->url : null;
    }
}
