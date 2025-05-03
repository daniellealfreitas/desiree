<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            auth()->user()->forceFill([
                'last_seen' => now(),
                'status' => auth()->user()->status === 'away' ? 'away' : 'online',
            ])->saveQuietly();
        }
        return $next($request);
    }
}