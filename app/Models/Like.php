<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;
use App\Models\UserPoint;

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
            // Só cria notificação se o like não for no próprio post
            if ($like->post->user_id !== $like->user_id) {
                Notification::create([
                    'user_id' => $like->post->user_id,
                    'sender_id' => $like->user_id,
                    'type' => 'like',
                    'post_id' => $like->post_id
                ]);

                // Adiciona pontos ao autor do post (se não for o mesmo usuário)
                UserPoint::addPoints(
                    $like->post->user_id,
                    'like_received',
                    5,
                    "Recebeu curtida de " . $like->user->name,
                    $like->post_id,
                    Post::class
                );
            }

            // Adiciona pontos ao usuário que curtiu
            UserPoint::addPoints(
                $like->user_id,
                'like',
                2,
                "Curtiu uma postagem" . ($like->post->user_id === $like->user_id ? " própria" : ""),
                $like->post_id,
                Post::class
            );
        });

        static::deleted(function ($like) {
            // Remove pontos do autor do post (se não for o mesmo usuário)
            if ($like->post->user_id !== $like->user_id) {
                UserPoint::removePoints(
                    $like->post->user_id,
                    'like_removed',
                    5,
                    "Perdeu curtida de " . $like->user->name,
                    $like->post_id,
                    Post::class
                );
            }

            // Remove pontos do usuário que descurtiu
            UserPoint::removePoints(
                $like->user_id,
                'unlike',
                2,
                "Removeu curtida de uma postagem",
                $like->post_id,
                Post::class
            );
        });
    }
}
