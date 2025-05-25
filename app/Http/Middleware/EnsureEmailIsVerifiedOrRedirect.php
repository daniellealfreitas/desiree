<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedOrRedirect
{
    /**
     * Handle an incoming request.
     *
     * Se o usuário estiver logado mas não verificado, redireciona para a página de verificação.
     * Se não estiver logado, redireciona para login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se não estiver logado, permite acesso (não redireciona)
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Se o usuário implementa MustVerifyEmail e não está verificado
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            // Se já está na página de verificação, permite acesso
            if ($request->routeIs('verification.notice') ||
                $request->routeIs('verification.verify') ||
                $request->routeIs('verification.send') ||
                $request->routeIs('verification.backup')) {
                return $next($request);
            }

            // Se está tentando fazer logout, permite
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            // Se está tentando acessar rotas de auth (login, register), permite
            if ($request->routeIs('login') || $request->routeIs('register')) {
                return $next($request);
            }

            // Caso contrário, redireciona para verificação
            return redirect()->route('verification.notice')
                ->with('warning', 'Por favor, verifique seu email antes de continuar.');
        }

        return $next($request);
    }
}
