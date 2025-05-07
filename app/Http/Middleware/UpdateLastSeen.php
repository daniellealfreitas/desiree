<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $userId = $user->id;

            // Usar cache para reduzir atualizações frequentes no banco de dados
            // Atualiza apenas a cada 60 segundos para o mesmo usuário
            $cacheKey = "user_last_seen_{$userId}";

            if (!Cache::has($cacheKey)) {
                $user->forceFill([
                    'last_seen' => now(),
                    // Mantém o status atual (away ou online)
                    'status' => $user->status === 'away' ? 'away' : 'online',
                ])->saveQuietly();

                // Armazena em cache por 1 minuto
                Cache::put($cacheKey, true, now()->addMinute());
            }
        }

        return $next($request);
    }
}