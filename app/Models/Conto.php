<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\ContosCategoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conto extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category_id',
        'user_id',
        'anonimo',
        'nome_anonimo',
        'number_views',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($conto) {
            $conto->slug = Str::slug($conto->title);
        });
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with ContosCategoria
    public function category()
    {
        return $this->belongsTo(ContosCategoria::class, 'category_id');
    }

    // Method to increment views
    public function incrementViews()
    {
        $this->increment('number_views');
    }
}
