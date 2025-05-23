<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Like;
use App\Models\UserPoint;
use App\Models\PostUserLike;
use App\Models\Comment;
use App\Models\Group;

class Post extends Model {
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'group_id',
        'image',
        'video',
        'likes_count'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

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
            // Calcular pontos base e bônus
            $pointsToAdd = 10; // Pontos base

            // Bônus por conteúdo multimídia
            if ($post->image) $pointsToAdd += 5;
            if ($post->video) $pointsToAdd += 10;

            // Bônus por tamanho do conteúdo
            if ($post->content && strlen($post->content) > 100) $pointsToAdd += 5;

            // Usar o novo sistema de pontos
            \App\Models\UserPoint::addPoints(
                $post->user_id,
                'post',
                $pointsToAdd,
                "Criou uma nova postagem" .
                ($post->image ? " com imagem" : "") .
                ($post->video ? " com vídeo" : ""),
                $post->id,
                \App\Models\Post::class
            );
        });

        static::deleted(function ($post) {
            // Usar o novo sistema para remover pontos
            \App\Models\UserPoint::removePoints(
                $post->user_id,
                'post_deleted',
                10,
                "Postagem excluída",
                null,
                null
            );
        });
    }
}
