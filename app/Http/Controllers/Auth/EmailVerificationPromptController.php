<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request)
    {
        // Verifica se o usuário está logado
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Se já está verificado, redireciona para dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Caso contrário, mostra a página de verificação
        return view('auth.verify-email');
    }
}
