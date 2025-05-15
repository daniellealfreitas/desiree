<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <!-- Notification Toast -->
        <x-notification-toast position="top-right" />

        <!-- Flash Messages -->
        <x-flash-messages />

        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <a href="{{ route('dashboard') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
                <img src="{{ asset('images/logo.png') }}" alt="Desiree Swing Club" class="img-fluid" style="max-width: 100px;" />
            </a>

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
                <flux:navbar.item icon="layout-grid" :href="route('busca')" :current="request()->routeIs('busca')" wire:navigate>
                    {{ __('Busca') }}
                </flux:navbar.item>
                <flux:navbar.item icon="layout-grid" :href="route('contos')" :current="request()->routeIs('contos')" wire:navigate>
                    {{ __('Contos') }}
                </flux:navbar.item>
                <flux:navbar.item icon="layout-grid" :href="route('ultimas')" :current="request()->routeIs('ultimas')" wire:navigate>
                    {{ __('Ultimas') }}
                </flux:navbar.item>
                <flux:navbar.item icon="layout-grid" :href="route('radar')" :current="request()->routeIs('radar')" wire:navigate>
                    {{ __('Radar') }}
                </flux:navbar.item>
                <flux:navbar.item icon="layout-grid" :href="route('grupos.index')" :current="request()->routeIs('grupos.*')" wire:navigate>
                    {{ __('Grupos') }}
                </flux:navbar.item>
                <flux:navbar.item icon="layout-grid" :href="route('Bate-Papo')" :current="request()->routeIs('Bate-Papo')" wire:navigate>
                    {{ __('Bate Papo') }}
                </flux:navbar.item>
                <flux:navbar.item icon="layout-grid" :href="route('caixa_de_mensagens')" :current="request('caixa_de_mensagens')->routeIs('caixa_de_mensagens')" wire:navigate>
                    {{ __('Caixa de Mensagens') }}
                </flux:navbar.item>

            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="mr-1.5 space-x-0.5 py-0!">
                <!-- Mini Cart -->
                @livewire('shop.mini-cart')

                <!-- Wallet Balance -->
                @livewire('wallet.wallet-balance')

                <flux:tooltip :content="__('Buscar')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Buscar')" />
                </flux:tooltip>
                <flux:tooltip :content="__('Repository')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="folder-git-2"
                        href="https://github.com/laravel/livewire-starter-kit"
                        target="_blank"
                        :label="__('Repository')"
                    />
                </flux:tooltip>
                <flux:tooltip :content="__('Documentation')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="book-open-text"
                        href="https://laravel.com/docs/starter-kits"
                        target="_blank"
                        label="Documentation"
                    />
                </flux:tooltip>
            </flux:navbar>

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="end">
                <flux:profile
                    class="cursor-pointer"
                    :initials="auth()->user()->initials()"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <img src="{{ auth()->user()->userPhotos->first() ? asset(auth()->user()->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                         class="h-full w-full object-cover"
                                         alt="{{ auth()->user()->name }}">
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('wallet.index')" icon="wallet" wire:navigate>
                            <div class="flex items-center justify-between w-full">
                                <span>{{ __('Carteira') }}</span>
                                <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">R$ {{ number_format(auth()->user()->wallet->balance, 2, ',', '.') }}</span>
                            </div>
                        </flux:menu.item>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="ml-1 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')">
                    <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="wallet" :href="route('wallet.index')" :current="request()->routeIs('wallet.*')" wire:navigate>
                        <div class="flex items-center justify-between w-full">
                            <span>{{ __('Carteira') }}</span>
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">R$ {{ number_format(auth()->user()->wallet->balance, 2, ',', '.') }}</span>
                        </div>
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts

        <!-- CSRF Token para requisições AJAX -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </body>
</html>
