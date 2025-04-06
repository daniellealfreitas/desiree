<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model {
    use HasFactory;

    protected $fillable = ['user_id', 'post_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function post() {
        return $this->belongsTo(Post::class);
    }

    // Ao curtir ou descurtir, atualiza os pontos do dono do post
    protected static function boot() {
        parent::boot();

        static::created(function ($like) {
            $userPoint = UserPoint::firstOrCreate(['user_id' => $like->post->user_id]);
            $userPoint->increment('points', 2);
            $like->post->user->updateLevel();
        });

        static::deleted(function ($like) {
            $userPoint = UserPoint::where('user_id', $like->post->user_id)->first();
            if ($userPoint) {
                $userPoint->decrement('points', 2);
                $like->post->user->updateLevel();
            }
        });
    }
}
