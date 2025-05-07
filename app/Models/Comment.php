<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'body'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            // Usar o novo sistema de pontos
            \App\Models\UserPoint::addPoints(
                $comment->user_id,
                'comment',
                5,
                "Comentou em uma postagem",
                $comment->id,
                \App\Models\Comment::class
            );
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
