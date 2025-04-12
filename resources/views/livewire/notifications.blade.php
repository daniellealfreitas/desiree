
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
                    @endif
                </flux:menu.item>
            @empty
                <flux:menu.item>{{ __('Nenhuma notificação') }}</flux:menu.item>
            @endforelse
        </flux:menu>
    </flux:dropdown>

