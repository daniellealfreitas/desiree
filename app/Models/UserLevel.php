<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model {
    use HasFactory;

    protected $fillable = ['level', 'min_points', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];
}
