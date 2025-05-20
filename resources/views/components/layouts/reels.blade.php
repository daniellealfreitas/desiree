<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('appearance', 'dark') }}">
    <head>
        @include('partials.head')
        <style>
            body {
                overflow: hidden;
                background-color: black;
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body class="min-h-screen bg-black">
        <!-- Notification Toast -->
        @livewire('toast-notification')

        <!-- Message Notifier (checks for new messages on all pages) -->
        @livewire('message-notifier')

        <!-- Botão de voltar -->
        <div class="fixed top-4 left-4 z-50">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-10 h-10 bg-black bg-opacity-50 rounded-full text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>

        {{ $slot }}

        @fluxScripts

        <!-- Livewire Scripts -->
        @livewireScripts

        <!-- Scripts para correções e notificações -->
        <script src="{{ asset('js/livewire-fix.js') }}"></script>
        <script src="{{ asset('js/toast-tester.js') }}"></script>
        <script src="{{ asset('js/toast-fix.js') }}"></script>
    </body>
</html>
