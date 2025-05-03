<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleOrAboveMiddleware
{
    /**
     * Handle an incoming request.
     * Permite acesso se o usuário for do role especificado OU superior (admin > vip > visitante)
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $roles = ['visitante' => 0, 'vip' => 1, 'admin' => 2];
        $user = Auth::user();
        if (!$user || !isset($roles[$user->role]) || $roles[$user->role] < $roles[$role]) {
            return redirect('/')->with('error', 'Acesso negado.');
        }
        return $next($request);
    }
}
