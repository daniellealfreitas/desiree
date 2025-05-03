<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Procura extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'procura_user');
    }
}