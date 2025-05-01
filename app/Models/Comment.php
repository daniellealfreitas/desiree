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
            $userPoint = UserPoint::firstOrCreate(['user_id' => $comment->user_id]);
            $userPoint->increment('points', 5);
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
