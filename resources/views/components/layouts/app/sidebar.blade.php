<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('appearance', 'dark') }}">
    <head>
        @include('partials.head')
        <!-- CSRF Token para requisições AJAX -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        @php
            $latestPhoto = auth()->user()->userPhotos()->latest()->first();
            $avatarUrl = $latestPhoto ? asset($latestPhoto->photo_path) : asset('images/users/avatar.jpg');
        @endphp

        <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" />
            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="map-pin" :href="route('radar')" >Radar</flux:navbar.item>
                <flux:navbar.item icon="inbox" badge="{{ auth()->user()->unreadMessagesCount() }}" :href="route('caixa_de_mensagens')">Mensagens</flux:navbar.item>

                @livewire('follow-request-notifications')
                <livewire:notifications />
            </flux:navbar>
            <flux:spacer />
            <flux:navbar class="me-4">
                <flux:navbar.item icon="currency-dollar" :href="route('wallet.index')" wire:navigate>
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm font-medium text-green-400 dark:text-green-400">R$ {{ number_format(auth()->user()->wallet->balance, 2, ',', '.') }}</span>
                    </div>
                </flux:navbar.item>
                <!-- Mini Cart -->
                @livewire('shop.mini-cart')

                <flux:navbar.item icon="magnifying-glass" href="#" label="Buscar" x-on:click.prevent="$dispatch('open-search-modal')" />
                <flux:navbar.item class="max-lg:hidden" icon="cog-6-tooth" :href="route('settings.profile')" label="Settings" />
                <flux:navbar.item class="max-lg:hidden" icon="information-circle" href="#" label="Help" />
            </flux:navbar>
            <flux:dropdown position="top" align="start">
                <flux:profile :avatar="$avatarUrl" />
                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.radio checked>{{ auth()->user()->name }}</flux:menu.radio>
                    </flux:menu.radio.group>

                    <flux:menu.separator />
                    <flux:menu.item icon="arrow-right-start-on-rectangle" :href="'/' . auth()->user()->username">Meu Perfil</flux:menu.item>
                    <flux:menu.item icon="user-plus" :href="route('follow.requests')" wire:navigate>
                        Solicitações para seguir
                        @php
                            $pendingCount = \App\Models\FollowRequest::where('receiver_id', auth()->id())
                                ->where('status', 'pending')
                                ->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs bg-red-500 text-white rounded-full">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </flux:menu.item>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" :href="route('settings.profile')">Configurações</flux:menu.item>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" :href="route('profile.visitors')">Meus Visitantes</flux:menu.item>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" :href="route('renovar-vip')">Renovar VIP</flux:menu.item>
                    <flux:menu.item icon="wallet" :href="route('wallet.index')">
                        <div class="flex items-center justify-between w-full">
                            <span>Minha Carteira</span>
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">R$ {{ number_format(auth()->user()->wallet->balance, 2, ',', '.') }}</span>
                        </div>
                    </flux:menu.item>
                    <flux:menu.item icon="credit-card" :href="route('meus-pagamentos')">Meus Pagamentos</flux:menu.item>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Sair') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Principal') }}
                    </flux:navlist.item>
                    <flux:navlist.group expandable heading="Loja" class="lg:grid" :expanded="request()->routeIs('shop.*')">
                        <flux:navlist.item icon="shopping-bag" :href="route('shop.index')" :current="request()->routeIs('shop.index')">
                            {{ __('Produtos') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="shopping-cart" :href="route('shop.cart')" :current="request()->routeIs('shop.cart')">
                            {{ __('Carrinho') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document-list" :href="route('shop.user.orders')" :current="request()->routeIs('shop.user.orders')">
                            {{ __('Meus Pedidos') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="heart" :href="route('shop.wishlist')" :current="request()->routeIs('shop.wishlist')">
                            {{ __('Lista de Desejos') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="arrow-down-tray" :href="route('shop.downloads')" :current="request()->routeIs('shop.downloads')">
                            {{ __('Meus Downloads') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    @if(auth()->user()->role === 'admin')
                    <flux:navlist.group expandable heading="Administração" class="lg:grid" :expanded="request()->routeIs('admin.*')">
                        <flux:navlist.item icon="chart-bar" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="cube" :href="route('admin.products')" :current="request()->routeIs('admin.products')">
                            {{ __('Produtos') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="folder" :href="route('admin.categories')" :current="request()->routeIs('admin.categories')">
                            {{ __('Categorias') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document-list" :href="route('admin.orders')" :current="request()->routeIs('admin.orders')">
                            {{ __('Pedidos') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="ticket" :href="route('admin.coupons')" :current="request()->routeIs('admin.coupons')">
                            {{ __('Cupons') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')">
                            {{ __('Usuários') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="wallet" :href="route('admin.wallets')" :current="request()->routeIs('admin.wallets')">
                            {{ __('Carteiras') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                    @endif
                    <flux:navlist.item icon="trophy" :href="route('points.history')" :current="request()->routeIs('points.history')" wire:navigate>
                        {{ __('Pontuação') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="eye" :href="route('profile.visitors')" :current="request()->routeIs('profile.visitors')" wire:navigate>
                        {{ __('Meus Visitantes') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="magnifying-glass-circle" :href="route('busca')" :current="request()->routeIs('busca')" wire:navigate>
                        {{ __('Busca') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="book-open" :href="route('contos')" :current="request()->routeIs('contos')" wire:navigate>
                        {{ __('Contos') }}
                    </flux:navlist.item>

                    <flux:navlist.group expandable heading="Feed" class="lg:grid" :expanded="request()->routeIs('feed.*')">
                        <flux:navlist.item icon="photo" :href="route('feed_imagens')">Imagens</flux:navlist.item>
                        <flux:navlist.item icon="video-camera" :href="route('feed_videos')"> Vídeos</flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.item icon="calendar-days" :href="route('events.index')" :current="request()->routeIs('events.*')" wire:navigate>
                        {{ __('Eventos') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="map-pin" :href="route('radar')" :current="request()->routeIs('radar')" wire:navigate>
                        {{ __('Radar') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('grupos.index')" :current="request()->routeIs('grupos.*')" wire:navigate>
                        {{ __('Grupos') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="inbox" badge="{{ auth()->user()->unreadMessagesCount() }}" :href="route('caixa_de_mensagens')" :current="request()->routeIs('caixa_de_mensagens')" wire:navigate>
                        {{ __('Caixa de Mensagens') }}
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

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile :name="auth()->user()->name"
                    :avatar="$avatarUrl"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <img src="{{ $avatarUrl }}"
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
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate >{{ __('Configurações') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Sair') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <!-- Mobile Search Button -->
            <button
                class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                x-on:click.prevent="$dispatch('open-search-modal')"
            >
                <x-flux::icon name="magnifying-glass" class="w-5 h-5" />
            </button>

            <flux:dropdown position="top" align="start">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    :avatar="$avatarUrl"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <img src="{{ $avatarUrl }}"
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
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Config') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Sair') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts

        <!-- Livewire Scripts -->
        @livewireScripts

        <!-- Componente de notificação de status de amigos -->
        <livewire:friend-status-notifier />

        <!-- Componente de busca modal -->
        <livewire:search-modal />

        <script>
            // Correção para o erro de showTooltip
            window.showTooltip = false;

            // Function to trigger confetti animation - optimized for performance
            window.triggerConfetti = function() {
                // Create a canvas element with all styles before adding to DOM
                const canvas = document.createElement('canvas');

                // Set all styles before appending to reduce reflows
                Object.assign(canvas.style, {
                    position: 'fixed',
                    top: '0',
                    left: '0',
                    width: '100%',
                    height: '100%',
                    pointerEvents: 'none',
                    zIndex: '9999'
                });

                // Get context and set dimensions once
                const ctx = canvas.getContext('2d');
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;

                // Now append to DOM after all styles are set
                document.body.appendChild(canvas);

                // Use a smaller number of particles for better performance
                const confettiCount = 200;
                const confetti = [];

                // Pre-calculate random values to avoid doing it in the animation loop
                for (let i = 0; i < confettiCount; i++) {
                    confetti.push({
                        x: Math.random() * canvas.width,
                        y: Math.random() * canvas.height - canvas.height,
                        r: Math.random() * 4 + 2, // Slightly smaller particles
                        dx: Math.random() * 3 - 1.5,
                        dy: Math.random() * 3 + 1,
                        color: `hsl(${Math.random() * 360}, 100%, 50%)`
                    });
                }

                // Throttled resize handler
                let resizeTimeout;
                const handleResize = () => {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(() => {
                        canvas.width = window.innerWidth;
                        canvas.height = window.innerHeight;
                    }, 100);
                };

                window.addEventListener('resize', handleResize);

                // Use requestAnimationFrame for smooth animation
                let animationId;
                function animate() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    // Batch drawing operations
                    for (let i = 0; i < confetti.length; i++) {
                        const p = confetti[i];
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                        ctx.fillStyle = p.color;
                        ctx.fill();

                        // Update position
                        p.x += p.dx;
                        p.y += p.dy;
                        if (p.y > canvas.height) p.y = -p.r;
                    }

                    animationId = requestAnimationFrame(animate);
                }

                // Start animation
                animationId = requestAnimationFrame(animate);

                // Clean up after animation
                setTimeout(() => {
                    cancelAnimationFrame(animationId);
                    window.removeEventListener('resize', handleResize);
                    canvas.remove();
                }, 2000);
            };

            // Function to trigger XP popup - optimized
            window.triggerXpPopup = function(points) {
                // Create popup with all styles before adding to DOM
                const popup = document.createElement('div');
                popup.textContent = `+${points} XP!`;

                // Apply all styles at once
                Object.assign(popup.style, {
                    position: 'fixed',
                    top: '50%',
                    left: '50%',
                    transform: 'translate(-50%, -50%)',
                    color: 'white',
                    fontSize: '2rem',
                    fontWeight: 'bold',
                    backgroundColor: '#3b82f6',
                    padding: '0.75rem 1.5rem',
                    borderRadius: '0.5rem',
                    boxShadow: '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
                    animation: 'pulse 2s infinite',
                    zIndex: '10000'
                });

                // Add keyframes for pulse animation if not already present
                if (!document.getElementById('xp-popup-style')) {
                    const style = document.createElement('style');
                    style.id = 'xp-popup-style';
                    style.textContent = `
                        @keyframes pulse {
                            0%, 100% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                            50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.05); }
                        }
                    `;
                    document.head.appendChild(style);
                }

                // Append to DOM after all styles are set
                document.body.appendChild(popup);

                // Remove after animation
                setTimeout(() => popup.remove(), 2000);
            };

            // Listener para o evento reward-earned (usando a sintaxe do Livewire 3)
            document.addEventListener('livewire:initialized', () => {
                // No Livewire 3, usamos Livewire.on em vez de Livewire.addEventListener
                Livewire.on('reward-earned', (data) => {
                    // Use requestIdleCallback if available for non-critical UI updates
                    if (window.requestIdleCallback) {
                        requestIdleCallback(() => {
                            window.triggerConfetti();
                            window.triggerXpPopup(data.points);
                        });
                    } else {
                        // Fallback to setTimeout for browsers that don't support requestIdleCallback
                        setTimeout(() => {
                            window.triggerConfetti();
                            window.triggerXpPopup(data.points);
                        }, 0);
                    }
                });
            });
        </script>
    </body>
</html>
