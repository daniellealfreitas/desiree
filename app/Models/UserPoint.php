<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\UserPointLog;
use App\Models\User;

class UserPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_points',
        'daily_points',
        'weekly_points',
        'monthly_points',
        'streak_days',
        'last_activity_at',
        'achievements',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'achievements' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Adiciona pontos ao usuário e registra no log
     *
     * @param int $userId ID do usuário
     * @param string $actionType Tipo de ação (post, comment, like, etc.)
     * @param int $points Quantidade de pontos
     * @param string|null $description Descrição da ação
     * @param int|null $relatedId ID da entidade relacionada
     * @param string|null $relatedType Tipo da entidade relacionada
     * @return UserPointLog
     */
    public static function addPoints($userId, $actionType, $points, $description = null, $relatedId = null, $relatedType = null)
    {
        // Obter ou criar registro de pontos do usuário
        $userPoint = self::firstOrCreate(['user_id' => $userId], [
            'total_points' => 0,
            'daily_points' => 0,
            'weekly_points' => 0,
            'monthly_points' => 0,
            'streak_days' => 0,
            'last_activity_at' => now(),
            'achievements' => [],
        ]);

        // Verificar se é um novo dia para atualizar streak
        $lastActivity = $userPoint->last_activity_at;
        $now = Carbon::now();

        if ($lastActivity && $lastActivity->diffInDays($now) == 1) {
            // Usuário ativo em dias consecutivos
            $userPoint->streak_days += 1;

            // Verificar conquistas de streak
            $streakAchievements = [
                3 => ['name' => 'streak_3_days', 'points' => 15, 'title' => '3 dias consecutivos'],
                7 => ['name' => 'streak_7_days', 'points' => 50, 'title' => '7 dias consecutivos'],
                30 => ['name' => 'streak_30_days', 'points' => 200, 'title' => '30 dias consecutivos'],
            ];

            $achievements = $userPoint->achievements ?: [];

            foreach ($streakAchievements as $days => $achievement) {
                if ($userPoint->streak_days == $days && !in_array($achievement['name'], $achievements)) {
                    $achievements[] = $achievement['name'];
                    $userPoint->achievements = $achievements;

                    // Adicionar pontos bônus pela conquista
                    $points += $achievement['points'];
                    $description = $description ? $description . " + Conquista: {$achievement['title']}" : "Conquista: {$achievement['title']}";
                }
            }
        } elseif ($lastActivity && $lastActivity->diffInDays($now) > 1) {
            // Quebrou o streak
            $userPoint->streak_days = 1;
        } elseif (!$lastActivity) {
            // Primeira atividade
            $userPoint->streak_days = 1;
        }

        // Atualizar pontos
        $userPoint->total_points += $points;
        $userPoint->daily_points += $points;
        $userPoint->weekly_points += $points;
        $userPoint->monthly_points += $points;
        $userPoint->last_activity_at = $now;
        $userPoint->save();

        // Calcular posição no ranking
        $rankingPosition = User::where('ranking_points', '>', $userPoint->user->ranking_points)->count() + 1;

        // Atualizar ranking_points na tabela users (para compatibilidade)
        $userPoint->user->increment('ranking_points', $points);

        // Criar notificação de pontos para o usuário
        \App\Models\Notification::create([
            'user_id' => $userId,
            'sender_id' => $userId, // O próprio usuário é o remetente para notificações de pontos
            'type' => 'points',
            'message' => json_encode([
                'points' => $points,
                'description' => $description,
                'action_type' => $actionType
            ]),
            'read' => false
        ]);

        // Registrar no log
        return \App\Models\UserPointLog::create([
            'user_id' => $userId,
            'action_type' => $actionType,
            'description' => $description,
            'points' => $points,
            'total_points' => $userPoint->total_points,
            'ranking_position' => $rankingPosition,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
        ]);
    }

    /**
     * Remove pontos do usuário e registra no log
     */
    public static function removePoints($userId, $actionType, $points, $description = null, $relatedId = null, $relatedType = null)
    {
        $userPoint = self::firstOrCreate(['user_id' => $userId], [
            'total_points' => 0,
            'daily_points' => 0,
            'weekly_points' => 0,
            'monthly_points' => 0,
            'streak_days' => 0,
            'last_activity_at' => now(),
            'achievements' => [],
        ]);

        // Atualizar pontos (não permitir valores negativos)
        $userPoint->total_points = max(0, $userPoint->total_points - $points);
        $userPoint->daily_points = max(0, $userPoint->daily_points - $points);
        $userPoint->weekly_points = max(0, $userPoint->weekly_points - $points);
        $userPoint->monthly_points = max(0, $userPoint->monthly_points - $points);
        $userPoint->save();

        // Calcular posição no ranking
        $rankingPosition = User::where('ranking_points', '>', $userPoint->user->ranking_points)->count() + 1;

        // Atualizar ranking_points na tabela users (para compatibilidade)
        $userPoint->user->decrement('ranking_points', min($points, $userPoint->user->ranking_points));

        // Registrar no log
        return \App\Models\UserPointLog::create([
            'user_id' => $userId,
            'action_type' => $actionType,
            'description' => $description,
            'points' => -$points,
            'total_points' => $userPoint->total_points,
            'ranking_position' => $rankingPosition,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
        ]);
    }

    /**
     * Reseta os pontos diários, semanais ou mensais
     */
    public static function resetPoints($period = 'daily')
    {
        $field = "{$period}_points";

        if (in_array($field, ['daily_points', 'weekly_points', 'monthly_points'])) {
            return self::query()->update([$field => 0]);
        }

        return false;
    }
}