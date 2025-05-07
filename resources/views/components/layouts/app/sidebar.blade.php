<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        @php
            $latestPhoto = auth()->user()->userPhotos()->latest()->first();
            $avatarUrl = $latestPhoto ? asset($latestPhoto->photo_path) : asset('images/default-avatar.jpg');
        @endphp

        <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" />
            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="map-pin" :href="route('radar')" >Radar</flux:navbar.item>
                <flux:navbar.item icon="inbox" badge="{{ auth()->user()->unreadMessagesCount() }}" :href="route('caixa_de_mensagens')">Mensagens</flux:navbar.item>
                <livewire:follow-request-notifications />
                <livewire:notifications />
            </flux:navbar>
            <flux:spacer />
            <flux:navbar class="me-4">
                <flux:navbar.item icon="magnifying-glass" href="#" label="Search" />
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
                    <flux:menu.item icon="arrow-right-start-on-rectangle" href="#">Meus Visitantes</flux:menu.item>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" :href="route('renovar-vip')">Renovar VIP</flux:menu.item>
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
                    </flux:navlist.group>
                    @endif
                    <flux:navlist.item icon="trophy" :href="route('points.history')" :current="request()->routeIs('points.history')" wire:navigate>
                        {{ __('Pontuação') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="magnifying-glass-circle" :href="route('busca')" :current="request()->routeIs('busca')" wire:navigate>
                        {{ __('Busca') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="book-open" :href="route('contos')" :current="request()->routeIs('contos')" wire:navigate>
                        {{ __('Contos') }}
                    </flux:navlist.item>

                    <flux:navlist.group expandable heading="Feed" class=" lg:grid">
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
                    <flux:navlist.item icon="chat-bubble-left-right" :href="route('bate_papo')" :current="request()->routeIs('bate_papo')" wire:navigate>
                        {{ __('Bate Papo') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="inbox" badge="{{ auth()->user()->unreadMessagesCount() }}" :href="route('caixa_de_mensagens')" :current="request()->routeIs('caixa_de_mensagens')" wire:navigate>
                        {{ __('Caixa de Mensagens') }}
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

        <script>
            // Function to trigger confetti animation
            window.triggerConfetti = function() {
                // Create a canvas element dynamically
                const canvas = document.createElement('canvas');
                document.body.appendChild(canvas);
                canvas.style.position = 'fixed';
                canvas.style.top = '0';
                canvas.style.left = '0';
                canvas.style.width = '100%';
                canvas.style.height = '100%';
                canvas.style.pointerEvents = 'none';
                const ctx = canvas.getContext('2d');
                const confettiCount = 300;
                const confetti = [];

                // Initialize confetti particles
                for (let i = 0; i < confettiCount; i++) {
                    confetti.push({
                        x: Math.random() * canvas.width,
                        y: Math.random() * canvas.height - canvas.height,
                        r: Math.random() * 6 + 2,
                        dx: Math.random() * 4 - 2,
                        dy: Math.random() * 4 + 2,
                        color: `hsl(${Math.random() * 360}, 100%, 50%)`
                    });
                }

                // Resize canvas to match the window size
                function resizeCanvas() {
                    canvas.width = window.innerWidth;
                    canvas.height = window.innerHeight;
                }
                resizeCanvas();
                window.addEventListener('resize', resizeCanvas);

                // Animation loop
                function animate() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    confetti.forEach(p => {
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                        ctx.fillStyle = p.color;
                        ctx.fill();
                        p.x += p.dx;
                        p.y += p.dy;
                        if (p.y > canvas.height) p.y = -p.r;
                    });
                    requestAnimationFrame(animate);
                }
                animate();

                // Remove canvas after 2 seconds
                setTimeout(() => {
                    window.removeEventListener('resize', resizeCanvas);
                    canvas.remove();
                }, 2000);
            };

            // Function to trigger XP popup
            window.triggerXpPopup = function(points) {
                // Create a popup element dynamically
                const popup = document.createElement('div');
                popup.textContent = `+${points} XP!`;
                popup.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-4xl font-bold bg-blue-500 px-6 py-3 rounded-lg shadow-lg animate-pulse';
                document.body.appendChild(popup);

                // Remove popup after 2 seconds
                setTimeout(() => {
                    popup.remove();
                }, 2000);
            };

            // Add keyboard shortcut to trigger animations
            document.addEventListener('keydown', function(event) {
                if (event.key === 'F10') {
                    // Trigger both animations when F10 is pressed
                    window.triggerConfetti();
                    window.triggerXpPopup(50); // Example: 50 XP
                }
            });

            // Listener para o evento reward-earned
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('reward-earned', (data) => {
                    window.triggerConfetti();
                    window.triggerXpPopup(data.points);
                });
            });
        </script>
    </body>
</html>
