<x-layouts.auth.simple title="Verificação de Email">
<div class="mt-4 flex flex-col gap-6">
    <div class="text-center">
        <div class="mx-auto mb-4 w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            Verifique seu email
        </h2>

        <p class="text-center text-gray-600 dark:text-gray-400">
            Enviamos um link de verificação para <strong>{{ Auth::user()->email }}</strong>
        </p>

        <p class="text-center text-sm text-gray-500 dark:text-gray-500 mt-2">
            Clique no link no email para verificar sua conta e acessar todos os recursos da plataforma.
        </p>
    </div>

    @if (session('warning'))
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium text-yellow-800 dark:text-yellow-200">
                    {{ session('warning') }}
                </span>
            </div>
        </div>
    @elseif (session('status') == 'registration-success')
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium text-green-800 dark:text-green-200">
                    Conta criada com sucesso!
                </span>
            </div>
            <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                Enviamos um email de verificação para você. Verifique sua caixa de entrada.
            </p>
        </div>
    @elseif (session('status') == 'verification-link-sent')
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium text-green-800 dark:text-green-200">
                    Email enviado com sucesso!
                </span>
            </div>
            <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                Um novo link de verificação foi enviado para o seu email.
            </p>
        </div>
    @endif

    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <p class="text-sm text-blue-800 dark:text-blue-200">
            <strong>Não recebeu o email?</strong><br>
            • Verifique sua caixa de spam<br>
            • Aguarde alguns minutos<br>
            • Clique no botão abaixo para reenviar
        </p>
    </div>

    <div class="flex flex-col items-center justify-between space-y-3">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reenviar email de verificação
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm cursor-pointer text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                Usar uma conta diferente
            </button>
        </form>
    </div>
</div>
</x-layouts.auth.simple>
