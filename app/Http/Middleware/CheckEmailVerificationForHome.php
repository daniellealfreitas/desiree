<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailVerificationForHome
{
    /**
     * Handle an incoming request.
     * 
     * Middleware específico para a home que redireciona usuários não verificados
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se não estiver logado, permite acesso
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Se o usuário implementa MustVerifyEmail e não está verificado
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Por favor, verifique seu email antes de continuar.');
        }

        return $next($request);
    }
}
