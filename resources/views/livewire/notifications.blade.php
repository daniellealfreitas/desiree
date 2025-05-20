
    <flux:dropdown>
        <flux:navbar.item icon="bell" badge="{{ $unreadCount }}">
            {{ __('Notificações') }}
        </flux:navbar.item>
        <flux:menu class="w-80">
            @forelse($notifications as $notification)
                <flux:menu.item
                    wire:click="markAsRead({{ $notification->id }})"
                    :class="$notification->read ? 'opacity-75' : ''"
                    href="#">
                    @if($notification->type === 'like')
                        <div class="flex items-center gap-2">
                            <img src="{{ $notification->sender->userPhotos->first() ? asset($notification->sender->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                 class="w-8 h-8 rounded-full">
                            <div class="text-sm">
                                <span class="font-semibold">{{ $notification->sender->name }}</span>
                                curtiu sua postagem
                                <div class="text-xs text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @elseif($notification->type === 'message')
                        <div class="flex items-center gap-2">
                            <img src="{{ $notification->sender->userPhotos->first() ? asset($notification->sender->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                 class="w-8 h-8 rounded-full">
                            <div class="text-sm">
                                <span class="font-semibold">{{ $notification->sender->name }}</span>
                                enviou uma mensagem para você
                                <div class="text-xs text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @elseif($notification->type === 'points')
                        @php
                            $pointsData = json_decode($notification->message, true);
                            $points = $pointsData['points'] ?? 0;
                            $description = $pointsData['description'] ?? '';
                            $actionType = $pointsData['action_type'] ?? '';
                        @endphp
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <x-flux::icon icon="trophy" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div class="text-sm">
                                <div class="flex items-center gap-1">
                                    <span class="font-semibold text-blue-600 dark:text-blue-400">+{{ $points }}</span>
                                    <span>pontos</span>
                                </div>
                                <p>{{ $description }}</p>
                                <div class="text-xs text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </flux:menu.item>
            @empty
                <flux:menu.item>{{ __('Nenhuma notificação') }}</flux:menu.item>
            @endforelse
        </flux:menu>
    </flux:dropdown>

