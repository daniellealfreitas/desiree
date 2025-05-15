<?php
use App\Models\User;
?>

<div>
    @if($showModal)
        <flux:modal wire:model="showModal" size="xl">
            <flux:modal.header>
                <flux:heading size="sm">Imagens de {{ User::find($userId)->name }}</flux:heading>
            </flux:modal.header>

            <flux:modal.body>
                @if($currentImage)
                    <div class="flex flex-col">
                        <div class="relative">
                            <img src="{{ $currentImage['url'] }}" alt="Imagem" class="w-full h-auto rounded-lg">
                            <button wire:click="$set('currentImage', null)" class="absolute top-2 right-2 bg-gray-800 bg-opacity-50 text-white rounded-full p-1">
                                <x-flux::icon name="x-mark" class="w-5 h-5" />
                            </button>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">{{ $currentImage['created_at'] }}</p>
                            <p class="mt-2">{{ $currentImage['content'] }}</p>
                            <div class="flex items-center gap-4 mt-2">
                                <div class="flex items-center gap-1">
                                    <x-flux::icon name="heart" class="w-5 h-5 text-red-500" />
                                    <span>{{ $currentImage['likes_count'] }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <x-flux::icon name="chat-bubble-left" class="w-5 h-5 text-blue-500" />
                                    <span>{{ $currentImage['comments_count'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @forelse($images as $index => $image)
                            <div wire:click="viewImage({{ $index }})" class="cursor-pointer relative group">
                                <img src="{{ $image['url'] }}" alt="Imagem" class="w-full h-48 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center rounded-lg">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white flex gap-3">
                                        <div class="flex items-center gap-1">
                                            <x-flux::icon name="heart" class="w-5 h-5" />
                                            <span>{{ $image['likes_count'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <x-flux::icon name="chat-bubble-left" class="w-5 h-5" />
                                            <span>{{ $image['comments_count'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8">
                                <x-flux::icon name="photo" class="w-12 h-12 mx-auto text-gray-400" />
                                <p class="mt-2 text-gray-500">Nenhuma imagem encontrada</p>
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
