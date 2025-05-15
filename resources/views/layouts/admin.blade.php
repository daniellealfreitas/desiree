<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }} - Administração</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-zinc-900">
        <!-- Notification Toast -->
        <x-notification-toast position="top-right" />

        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-zinc-800 shadow-md transform transition-transform duration-300 lg:translate-x-0" id="sidebar">
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <span class="text-xl font-semibold text-gray-800 dark:text-white">Admin</span>
                </div>
                <button class="p-2 rounded-md lg:hidden" id="closeSidebar">
                    <flux:icon name="x-mark" class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                </button>
            </div>
            <nav class="mt-5 px-2 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <flux:icon name="home" class="mr-3 h-6 w-6 {{ request()->routeIs('admin.dashboard') ? 'text-gray-500 dark:text-gray-300' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
                    Dashboard
                </a>

                <a href="{{ route('admin.products') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.products') ? 'bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <flux:icon name="shopping-bag" class="mr-3 h-6 w-6 {{ request()->routeIs('admin.products') ? 'text-gray-500 dark:text-gray-300' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
                    Produtos
                </a>

                <a href="{{ route('admin.categories') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.categories') ? 'bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <flux:icon name="folder" class="mr-3 h-6 w-6 {{ request()->routeIs('admin.categories') ? 'text-gray-500 dark:text-gray-300' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
                    Categorias
                </a>

                <a href="{{ route('admin.orders') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.orders') ? 'bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <flux:icon name="clipboard-document-list" class="mr-3 h-6 w-6 {{ request()->routeIs('admin.orders') ? 'text-gray-500 dark:text-gray-300' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
                    Pedidos
                </a>

                <a href="{{ route('admin.users') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.users') ? 'bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <flux:icon name="users" class="mr-3 h-6 w-6 {{ request()->routeIs('admin.users') ? 'text-gray-500 dark:text-gray-300' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
                    Usuários
                </a>

                <a href="{{ route('admin.wallets') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.wallets') ? 'bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <flux:icon name="wallet" class="mr-3 h-6 w-6 {{ request()->routeIs('admin.wallets') ? 'text-gray-500 dark:text-gray-300' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
                    Carteiras
                </a>

                <a href="{{ route('admin.coupons') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.coupons') ? 'bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <flux:icon name="ticket" class="mr-3 h-6 w-6 {{ request()->routeIs('admin.coupons') ? 'text-gray-500 dark:text-gray-300' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
                    Cupons
                </a>

                <a href="{{ route('admin.settings') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.settings') ? 'bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <flux:icon name="cog-6-tooth" class="mr-3 h-6 w-6 {{ request()->routeIs('admin.settings') ? 'text-gray-500 dark:text-gray-300' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
                    Configurações
                </a>

                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('home') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-white">
                        <flux:icon name="arrow-left" class="mr-3 h-6 w-6 text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" />
                        Voltar ao Site
                    </a>
                </div>
            </nav>
        </div>

        <!-- Mobile header -->
        <div class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-white dark:bg-zinc-800 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between h-16 px-4">
                <button class="p-2 rounded-md" id="openSidebar">
                    <flux:icon name="bars-3" class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                </button>
                <div class="flex items-center">
                    <span class="text-xl font-semibold text-gray-800 dark:text-white">Admin</span>
                </div>
                <div>
                    <!-- User dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                            <span class="sr-only">Abrir menu do usuário</span>
                            <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                        </button>
                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-zinc-800 ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                            <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700" role="menuitem">Seu Perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700" role="menuitem">Sair</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="lg:pl-64">
            <!-- Desktop header -->
            <div class="hidden lg:flex lg:sticky lg:top-0 lg:z-40 lg:h-16 lg:items-center lg:bg-white lg:dark:bg-zinc-800 lg:border-b lg:border-gray-200 lg:dark:border-gray-700 lg:px-6">
                <div class="flex-1"></div>
                <div class="flex items-center space-x-4">
                    <!-- Theme toggle -->
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-zinc-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-zinc-700 rounded-lg text-sm p-2.5">
                        <flux:icon name="sun" class="hidden dark:block h-5 w-5" />
                        <flux:icon name="moon" class="block dark:hidden h-5 w-5" />
                    </button>

                    <!-- User dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                        </button>
                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-zinc-800 ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                            <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700" role="menuitem">Seu Perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700" role="menuitem">Sair</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="py-10 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    <script>
        // Mobile sidebar toggle
        document.getElementById('openSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
        });

        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
        });

        // Theme toggle - usando a função global definida em app.js
        document.getElementById('theme-toggle').addEventListener('click', function() {
            // Verificar se a função global está disponível
            if (typeof window.toggleTheme === 'function') {
                window.toggleTheme();
            } else {
                // Fallback caso a função global não esteja disponível
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
