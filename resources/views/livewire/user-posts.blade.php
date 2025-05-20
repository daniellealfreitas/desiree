<?php
use App\Models\User;
use Illuminate\Support\Facades\Storage;
?>

<div>
    @if($showModal)
        <flux:modal wire:model="showModal" size="xl" >
            <flux:modal.header>
                <flux:heading size="sm">Postagens de {{ User::find($userId)->name }}</flux:heading>
            </flux:modal.header>

            <flux:modal.body>
                <div class="space-y-6 max-h-[70vh] overflow-y-auto p-2">
                    @forelse($posts as $post)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                            <div class="p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="relative">
                                        <img src="{{ $this->getAvatar($post->user_id) }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full object-cover">
                                        <livewire:user-status-indicator :userId="$post->user_id" />
                                    </div>
                                    <div>
                                        <a href="/{{ $post->user->username }}" class="font-semibold hover:underline">{{ $post->user->name }}</a>
                                        <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <p class="mb-4">{{ $post->content }}</p>

                                @if($post->image)
                                    <img src="{{ Storage::url($post->image) }}" alt="Imagem do post" class="w-full h-auto rounded-lg mb-4">
                                @endif

                                @if($post->video)
                                    <video src="{{ Storage::url($post->video) }}" controls class="w-full h-auto rounded-lg mb-4"></video>
                                @endif

                                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-3">
                                    <div class="flex items-center gap-4">
                                        <button
                                            wire:click="toggleLike({{ $post->id }})"
                                            class="flex items-center gap-1 {{ $likeStatus[$post->id] ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }} transition-colors"
                                        >
                                            <x-flux::icon name="{{ $likeStatus[$post->id] ? 'heart-solid' : 'heart' }}" class="w-5 h-5" />
                                            <span>{{ $post->likes->count() }}</span>
                                        </button>

                                        <div class="flex items-center gap-1 text-gray-500">
                                            <x-flux::icon name="chat-bubble-left" class="w-5 h-5" />
                                            <span>{{ $post->comments->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <x-flux::icon name="document-text" class="w-12 h-12 mx-auto text-gray-400" />
                            <p class="mt-2 text-gray-500">Nenhuma postagem encontrada</p>
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
