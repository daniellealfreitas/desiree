<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPointLog extends Model
{
    use HasFactory;

    protected $table = 'user_points_log';

    protected $fillable = [
        'user_id',
        'action_type',
        'description',
        'points',
        'total_points',
        'ranking_position',
        'related_id',
        'related_type',
    ];

    /**
     * Obter o usuário associado a este registro de pontos
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obter a entidade relacionada (polimórfica)
     */
    public function related()
    {
        if ($this->related_id && $this->related_type) {
            return $this->morphTo('related');
        }
        return null;
    }

    /**
     * Obter o ícone correspondente ao tipo de ação
     */
    public function getIconAttribute()
    {
        return match($this->action_type) {
            'post' => 'document-text',
            'comment' => 'chat-bubble-left',
            'like' => 'heart',
            'login' => 'arrow-right-on-rectangle',
            'achievement' => 'trophy',
            'streak' => 'calendar',
            'follow' => 'user-plus',
            'share' => 'share',
            default => 'star',
        };
    }

    /**
     * Obter a cor correspondente ao tipo de ação
     */
    public function getColorAttribute()
    {
        return match($this->action_type) {
            'post' => 'blue',
            'comment' => 'green',
            'like' => 'red',
            'login' => 'purple',
            'achievement' => 'yellow',
            'streak' => 'orange',
            'follow' => 'indigo',
            'share' => 'teal',
            default => 'gray',
        };
    }

    /**
     * Obter a descrição formatada da ação
     */
    public function getFormattedDescriptionAttribute()
    {
        if ($this->description) {
            return $this->description;
        }

        return match($this->action_type) {
            'post' => 'Criou uma nova postagem',
            'comment' => 'Comentou em uma postagem',
            'like' => 'Curtiu uma postagem',
            'login' => 'Fez login no sistema',
            'achievement' => 'Desbloqueou uma conquista',
            'streak' => 'Manteve sequência de atividade',
            'follow' => 'Seguiu um usuário',
            'share' => 'Compartilhou conteúdo',
            default => 'Realizou uma ação',
        };
    }

    /**
     * Obter o tempo formatado
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
