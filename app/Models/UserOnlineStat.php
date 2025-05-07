<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserOnlineStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'minutes_online',
        'minutes_away',
        'minutes_dnd',
        'last_status_change',
        'current_status',
    ];

    protected $casts = [
        'date' => 'date',
        'last_status_change' => 'datetime',
    ];

    /**
     * Relacionamento com o usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obter ou criar estatísticas para o usuário na data atual
     */
    public static function getOrCreateForToday($userId)
    {
        // Set date to startOfDay to ensure consistent date format without time components
        $today = Carbon::today()->startOfDay();
        $todayStr = $today->format('Y-m-d');
        
        // Debug log to trace data formats
        \Log::debug('UserOnlineStat::getOrCreateForToday', [
            'user_id' => $userId,
            'date' => $todayStr,
            'date_type' => gettype($todayStr),
            'date_carbon' => $today->toDateTimeString(),
            'now' => now()->toDateTimeString(),
            'current_timezone' => config('app.timezone')
        ]);
        
        try {
            // First try to find existing record with an exact date match
            $existingRecord = self::where('user_id', $userId)
                ->whereDate('date', $todayStr)
                ->first();
                
            if ($existingRecord) {
                \Log::debug('Found existing record for today', [
                    'record_id' => $existingRecord->id,
                    'date' => $existingRecord->date->toDateString()
                ]);
                return $existingRecord;
            }
            
            // If no existing record, create a new one
            \Log::debug('Creating new record for today', [
                'user_id' => $userId,
                'date' => $todayStr
            ]);
            
            return self::create([
                'user_id' => $userId,
                'date' => $todayStr,
                'minutes_online' => 0,
                'minutes_away' => 0,
                'minutes_dnd' => 0,
                'current_status' => 'offline',
                'last_status_change' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in UserOnlineStat::getOrCreateForToday', [
                'user_id' => $userId,
                'date' => $todayStr,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // One more attempt to find the record in case it was created
            // between our check and create operations (race condition)
            $existingRecord = self::where('user_id', $userId)
                ->whereDate('date', $todayStr)
                ->first();
                
            if ($existingRecord) {
                \Log::debug('Found existing record after error', [
                    'record_id' => $existingRecord->id
                ]);
                return $existingRecord;
            }
            
            // If we can't find it, rethrow the exception
            throw $e;
        }
    }

    /**
     * Atualizar estatísticas quando o status do usuário muda
     */
    public static function updateOnStatusChange($userId, $newStatus)
    {
        try {
            \Log::debug('UserOnlineStat::updateOnStatusChange start', [
                'user_id' => $userId,
                'new_status' => $newStatus
            ]);
            
            $stats = self::getOrCreateForToday($userId);
            $now = now();
            
            // Se houver um status anterior, calcular o tempo decorrido
            if ($stats->last_status_change) {
                $minutesElapsed = $now->diffInMinutes($stats->last_status_change);
                
                \Log::debug('Minutes elapsed since last status change', [
                    'minutes' => $minutesElapsed,
                    'last_change' => $stats->last_status_change->toDateTimeString(),
                    'now' => $now->toDateTimeString(),
                    'current_status' => $stats->current_status,
                ]);
                
                // Adicionar o tempo ao status anterior
                switch ($stats->current_status) {
                    case 'online':
                        $stats->minutes_online += $minutesElapsed;
                        break;
                    case 'away':
                        $stats->minutes_away += $minutesElapsed;
                        break;
                    case 'dnd':
                        $stats->minutes_dnd += $minutesElapsed;
                        break;
                    // Não contabilizamos tempo offline
                }
            }
            
            // Atualizar para o novo status
            $stats->current_status = $newStatus;
            $stats->last_status_change = $now;
            
            \Log::debug('Saving stats after status change', [
                'stats_id' => $stats->id,
                'date' => $stats->date->toDateString(),
                'new_status' => $newStatus,
                'minutes_online' => $stats->minutes_online,
                'minutes_away' => $stats->minutes_away,
                'minutes_dnd' => $stats->minutes_dnd
            ]);
            
            $stats->save();
            
            return $stats;
        } catch (\Exception $e) {
            \Log::error('Error in UserOnlineStat::updateOnStatusChange', [
                'user_id' => $userId,
                'new_status' => $newStatus,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Obter o tempo total online (incluindo away e dnd)
     */
    public function getTotalOnlineTimeAttribute()
    {
        return $this->minutes_online + $this->minutes_away + $this->minutes_dnd;
    }

    /**
     * Obter estatísticas da semana atual
     */
    public static function getCurrentWeekStats($userId)
    {
        $startOfWeek = Carbon::now()->startOfWeek()->startOfDay();
        $endOfWeek = Carbon::now()->endOfWeek()->startOfDay();
        
        \Log::debug('Getting current week stats', [
            'user_id' => $userId,
            'start_of_week' => $startOfWeek->format('Y-m-d'),
            'end_of_week' => $endOfWeek->format('Y-m-d')
        ]);
        
        return self::where('user_id', $userId)
            ->whereDate('date', '>=', $startOfWeek->format('Y-m-d'))
            ->whereDate('date', '<=', $endOfWeek->format('Y-m-d'))
            ->get();
    }

    /**
     * Obter estatísticas do mês atual
     */
    public static function getCurrentMonthStats($userId)
    {
        $startOfMonth = Carbon::now()->startOfMonth()->startOfDay();
        $endOfMonth = Carbon::now()->endOfMonth()->startOfDay();
        
        \Log::debug('Getting current month stats', [
            'user_id' => $userId,
            'start_of_month' => $startOfMonth->format('Y-m-d'),
            'end_of_month' => $endOfMonth->format('Y-m-d')
        ]);
        
        return self::where('user_id', $userId)
            ->whereDate('date', '>=', $startOfMonth->format('Y-m-d'))
            ->whereDate('date', '<=', $endOfMonth->format('Y-m-d'))
            ->get();
    }
}
