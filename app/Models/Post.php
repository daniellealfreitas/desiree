<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Like;
use App\Models\UserPoint;
use App\Models\PostUserLike;
use App\Models\Comment;

class Post extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image',
        'video',
        'likes_count'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'post_user_likes')->withTimestamps();
    }

    public function isLikedBy($user)
    {
        return $user ? $this->likedByUsers->contains($user) : false;
    }

    // Ao criar ou deletar um post, atualiza os pontos do usuário
    protected static function boot() {
        parent::boot();

        static::created(function ($post) {
            $userPoint = UserPoint::firstOrCreate(['user_id' => $post->user_id]);
            $userPoint->increment('points', 10);
            $post->user->updateLevel();
        });

        static::deleted(function ($post) {
            $userPoint = UserPoint::where('user_id', $post->user_id)->first();
            if ($userPoint) {
                $userPoint->decrement('points', 10);
                $post->user->updateLevel();
            }
        });
    }
}
