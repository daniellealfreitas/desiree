<?php
use App\Models\User;
?>

<div>
    @if($showModal)
        <flux:modal wire:model="showModal" size="xl">
            <flux:modal.header>
                <flux:heading size="sm">Vídeos de {{ User::find($userId)->name }}</flux:heading>
            </flux:modal.header>

            <flux:modal.body>
                @if($currentVideo)
                    <div class="flex flex-col">
                        <div class="relative">
                            <video src="{{ $currentVideo['url'] }}" controls class="w-full h-auto rounded-lg"></video>
                            <button wire:click="$set('currentVideo', null)" class="absolute top-2 right-2 bg-gray-800 bg-opacity-50 text-white rounded-full p-1">
                                <x-flux::icon name="x-mark" class="w-5 h-5" />
                            </button>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">{{ $currentVideo['created_at'] }}</p>
                            <p class="mt-2">{{ $currentVideo['content'] }}</p>
                            <div class="flex items-center gap-4 mt-2">
                                <div class="flex items-center gap-1">
                                    <x-flux::icon name="heart" class="w-5 h-5 text-red-500" />
                                    <span>{{ $currentVideo['likes_count'] }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <x-flux::icon name="chat-bubble-left" class="w-5 h-5 text-blue-500" />
                                    <span>{{ $currentVideo['comments_count'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($videos as $index => $video)
                            <div wire:click="viewVideo({{ $index }})" class="cursor-pointer relative group">
                                <video src="{{ $video['url'] }}" class="w-full h-48 object-cover rounded-lg"></video>
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center rounded-lg">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white flex gap-3">
                                        <div class="flex items-center gap-1">
                                            <x-flux::icon name="heart" class="w-5 h-5" />
                                            <span>{{ $video['likes_count'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <x-flux::icon name="chat-bubble-left" class="w-5 h-5" />
                                            <span>{{ $video['comments_count'] }}</span>
                                        </div>
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <x-flux::icon name="play" class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8">
                                <x-flux::icon name="video-camera" class="w-12 h-12 mx-auto text-gray-400" />
                                <p class="mt-2 text-gray-500">Nenhum vídeo encontrado</p>
                            </div>
                        @endforelse
                    </div>
                @endif
            </flux:modal.body>

            <flux:modal.footer>
                <x-flux.button wire:click="closeModal" variant="secondary">Fechar</x-flux.button>
            </flux:modal.footer>
        </flux:modal>
    @endif
</div>
