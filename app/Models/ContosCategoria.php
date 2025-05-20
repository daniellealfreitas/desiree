<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContosCategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description'
    ];

    protected static function boot()
    {
        parent::boot();
        
       
    }

    public function contos()
    {
        return $this->hasMany(Conto::class, 'category_id');
    }
}
