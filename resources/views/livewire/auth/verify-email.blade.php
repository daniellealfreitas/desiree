<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component
 {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="mt-4 flex flex-col gap-6">
    <div class="text-center">
        <div class="mx-auto mb-4 w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>

        <flux:heading size="lg" class="mb-2">
            {{ __('Verifique seu email') }}
        </flux:heading>

        <flux:text class="text-center text-zinc-600 dark:text-zinc-400">
            {{ __('Enviamos um link de verificação para') }} <strong>{{ Auth::user()->email }}</strong>
        </flux:text>

        <flux:text class="text-center text-sm text-zinc-500 dark:text-zinc-500 mt-2">
            {{ __('Clique no link no email para verificar sua conta e acessar todos os recursos da plataforma.') }}
        </flux:text>
    </div>

    @if (session('warning'))
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <flux:text class="font-medium text-yellow-800 dark:text-yellow-200">
                    {{ session('warning') }}
                </flux:text>
            </div>
        </div>
    @elseif (session('status') == 'registration-success')
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <flux:text class="font-medium text-green-800 dark:text-green-200">
                    {{ __('Conta criada com sucesso!') }}
                </flux:text>
            </div>
            <flux:text class="text-sm text-green-700 dark:text-green-300 mt-1">
                {{ __('Enviamos um email de verificação para você. Verifique sua caixa de entrada.') }}
            </flux:text>
        </div>
    @elseif (session('status') == 'verification-link-sent')
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <flux:text class="font-medium text-green-800 dark:text-green-200">
                    {{ __('Email enviado com sucesso!') }}
                </flux:text>
            </div>
            <flux:text class="text-sm text-green-700 dark:text-green-300 mt-1">
                {{ __('Um novo link de verificação foi enviado para o seu email.') }}
            </flux:text>
        </div>
    @endif

    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <flux:text class="text-sm text-blue-800 dark:text-blue-200">
            <strong>{{ __('Não recebeu o email?') }}</strong><br>
            • {{ __('Verifique sua caixa de spam') }}<br>
            • {{ __('Aguarde alguns minutos') }}<br>
            • {{ __('Clique no botão abaixo para reenviar') }}
        </flux:text>
    </div>

    <div class="flex flex-col items-center justify-between space-y-3">
        <flux:button wire:click="sendVerification" variant="primary" class="w-full">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            {{ __('Reenviar email de verificação') }}
        </flux:button>

        <flux:link class="text-sm cursor-pointer text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200" wire:click="logout">
            {{ __('Usar uma conta diferente') }}
        </flux:link>
    </div>
</div>
