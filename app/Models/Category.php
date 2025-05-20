<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    /**
     * Produtos nesta categoria (one-to-many).
     * Comentado porque a coluna category_id não existe na tabela products
     */
    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }

    /**
     * Produtos relacionados a esta categoria (many-to-many).
     */
    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    /**
     * Categoria pai (para subcategorias).
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Subcategorias.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Escopo para categorias ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Escopo para categorias principais (sem parent).
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}
