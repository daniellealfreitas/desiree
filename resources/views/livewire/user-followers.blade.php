<?php
use Illuminate\Support\Facades\Auth;
?>

<div>
    @if($showModal)
        <flux:modal wire:model="showModal" size="lg">
            <flux:modal.header>
                <flux:heading size="sm">Seguidores ({{ count($followers) }})</flux:heading>
            </flux:modal.header>

            <flux:modal.body>
                <div class="space-y-4">
                    @forelse($followers as $user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <img src="{{ $this->getAvatar($user->id) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover">
                                    <livewire:user-status-indicator :userId="$user->id" />
                                </div>
                                <div>
                                    <a href="/{{ $user->username }}" class="font-semibold hover:underline text-gray-300">{{ $user->name }}</a>
                                    <p class="text-sm text-gray-500">{{ '@' . $user->username }}</p>
                                </div>
                            </div>

                            @if($user->id !== Auth::id())
                                <x-flux.button
                                    wire:click="toggleFollow({{ $user->id }})"
                                    variant="{{ $followStatus[$user->id] ? 'secondary' : 'primary' }}"
                                    size="sm"
                                >
                                    {{ $followStatus[$user->id] ? 'Deixar de Seguir' : 'Seguir' }}
                                </x-flux.button>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <x-flux::icon name="users" class="w-12 h-12 mx-auto text-gray-400" />
                            <p class="mt-2 text-gray-500">Nenhum seguidor ainda</p>
                        </div>
                    @endforelse
                </div>
            </flux:modal.body>

            <flux:modal.footer>
                <x-flux.button wire:click="closeModal" variant="primary">Fechar</x-flux.button>
            </flux:modal.footer>
        </flux:modal>
    @endif
</div>
