<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" />
            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="map-pin" href="#" >Radar</flux:navbar.item>
                <flux:navbar.item icon="inbox" badge="12" href="#">Mensagens</flux:navbar.item>
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
                <flux:profile :avatar="auth()->user()->userPhotos->first() ? asset(auth()->user()->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg')" />
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
                    <flux:menu.item icon="arrow-right-start-on-rectangle" href="#">Renovar VIP</flux:menu.item>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" href="#">Meus Pagamentos</flux:menu.item>
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
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Principal') }}
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

                    <flux:navlist.item icon="calendar-days" :href="route('programacao')" :current="request()->routeIs('programacao')" wire:navigate>
                        {{ __('Programação') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="map-pin" :href="route('radar')" :current="request()->routeIs('radar')" wire:navigate>
                        {{ __('Radar') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('grupos')" :current="request()->routeIs('grupos')" wire:navigate>
                        {{ __('Grupos') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="chat-bubble-left-right" :href="route('bate_papo')" :current="request()->routeIs('bate_papo')" wire:navigate>
                        {{ __('Bate Papo') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="inbox" badge="12" :href="route('caixa_de_mensagens')" :current="request()->routeIs('caixa_de_mensagens')" wire:navigate>
                        {{ __('Caixa de Mensagens') }}
                    </flux:navlist.item>
                </flux:navlist.group>                
            </flux:navlist>
            <flux:spacer />    
        
       

         

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile :name="auth()->user()->name"
                    :avatar="auth()->user()->userPhotos->first() ? asset(auth()->user()->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg')"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
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
                    :avatar="auth()->user()->userPhotos->first() ? asset(auth()->user()->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg')"
                    icon-trailing="chevron-down"
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
    </body>
</html>
