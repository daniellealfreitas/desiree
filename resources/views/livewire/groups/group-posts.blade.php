<div>
    <!-- Formulário de criação de postagem -->
    @if($isMember)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
            @if(!$showPostForm)
                <button
                    wire:click="togglePostForm"
                    class="w-full text-left px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                >
                    O que você está pensando?
                </button>
            @else
                <form wire:submit.prevent="createPost">
                    <div class="mb-4">
                        <flux:textarea
                            wire:model="content"
                            placeholder="O que você está pensando?"
                            rows="3"
                            class="w-full"
                        ></flux:textarea>
                        @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-wrap gap-4 mb-4">
                        <!-- Preview da imagem -->
                        @if($image)
                            <div class="relative">
                                <img src="{{ $image->temporaryUrl() }}" class="h-24 w-auto rounded-lg object-cover">
                                <button
                                    type="button"
                                    wire:click="$set('image', null)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1"
                                >
                                    <x-flux::icon icon="x-mark" class="w-4 h-4" />
                                </button>
                            </div>
                        @endif

                        <!-- Preview do vídeo -->
                        @if($video)
                            <div class="relative">
                                <div class="h-24 w-32 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <x-flux::icon icon="film" class="w-8 h-8 text-gray-500 dark:text-gray-400" />
                                </div>
                                <button
                                    type="button"
                                    wire:click="$set('video', null)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1"
                                >
                                    <x-flux::icon icon="x-mark" class="w-4 h-4" />
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex space-x-2">
                            <label for="image-upload" class="cursor-pointer flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                <x-flux::icon icon="photo" class="w-5 h-5 mr-1" />
                                <span>Foto</span>
                                <input id="image-upload" type="file" wire:model="image" class="hidden" accept="image/*">
                            </label>

                            <label for="video-upload" class="cursor-pointer flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                <x-flux::icon icon="film" class="w-5 h-5 mr-1" />
                                <span>Vídeo</span>
                                <input id="video-upload" type="file" wire:model="video" class="hidden" accept="video/*">
                            </label>
                        </div>

                        <div class="flex space-x-2">
                            <flux:button type="button" color="secondary" wire:click="togglePostForm">
                                Cancelar
                            </flux:button>

                            <flux:button type="submit" color="primary">
                                Publicar
                            </flux:button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    @endif

    <!-- Lista de postagens -->
    <div class="space-y-6">
        @if($posts->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
                <x-flux::icon icon="document-text" class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma postagem ainda</h3>

                @if($isMember)
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Seja o primeiro a compartilhar algo com o grupo!</p>
                    <flux:button wire:click="togglePostForm" color="primary">
                        Criar Postagem
                    </flux:button>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Entre no grupo para ver e criar postagens.</p>
                @endif
            </div>
        @else
            @foreach($posts as $post)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <!-- Cabeçalho da postagem -->
                    <div class="p-4 flex items-start justify-between">
                        <div class="flex items-center">
                            <a href="{{ route('user.profile', $post->user->username) }}" class="flex-shrink-0">
                                <img
                                    src="{{ $post->user->userPhotos->first() ? asset('storage/' . $post->user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                    alt="{{ $post->user->name }}"
                                    class="w-10 h-10 rounded-full object-cover"
                                >
                            </a>

                            <div class="ml-3">
                                <a href="{{ route('user.profile', $post->user->username) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">
                                    {{ $post->user->name }}
                                </a>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <!-- Menu de opções da postagem -->
                        @if(auth()->id() === $post->user_id || $canManage)
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <x-flux::icon icon="ellipsis-vertical" class="w-5 h-5" />
                                </button>

                                <div
                                    x-show="open"
                                    @click.away="open = false"
                                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-10"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                >
                                    <button
                                        wire:click="deletePost({{ $post->id }})"
                                        @click="open = false"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Conteúdo da postagem -->
                    @if($post->content)
                        <div class="px-4 pb-3">
                            <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $post->content }}</p>
                        </div>
                    @endif

                    <!-- Imagem da postagem -->
                    @if($post->image)
                        <div class="pb-3">
                            <img
                                src="{{ asset('storage/' . $post->image) }}"
                                alt="Imagem da postagem"
                                class="w-full h-auto"
                            >
                        </div>
                    @endif

                    <!-- Vídeo da postagem -->
                    @if($post->video)
                        <div class="pb-3">
                            <video
                                src="{{ asset('storage/' . $post->video) }}"
                                controls
                                class="w-full h-auto"
                            ></video>
                        </div>
                    @endif

                    <!-- Estatísticas e ações -->
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between text-sm">
                            <div class="text-gray-500 dark:text-gray-400">
                                @if($post->likes_count > 0)
                                    <span>{{ $post->likes_count }} {{ $post->likes_count == 1 ? 'curtida' : 'curtidas' }}</span>
                                @endif

                                @if($post->likes_count > 0 && $post->comments->count() > 0)
                                    <span class="mx-1">•</span>
                                @endif

                                @if($post->comments->count() > 0)
                                    <span>{{ $post->comments->count() }} {{ $post->comments->count() == 1 ? 'comentário' : 'comentários' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Botões de ação -->
                    <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 flex">
                        <button
                            class="flex-1 flex items-center justify-center py-1 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md"
                            wire:click="$emit('toggleLike', {{ $post->id }})"
                        >
                            @if($post->isLikedBy(auth()->user()))
                                <x-flux::icon icon="heart" variant="solid" class="w-5 h-5 mr-2 text-red-500" />
                                <span>Curtido</span>
                            @else
                                <x-flux::icon icon="heart" class="w-5 h-5 mr-2" />
                                <span>Curtir</span>
                            @endif
                        </button>

                        <button
                            class="flex-1 flex items-center justify-center py-1 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md"
                            wire:click="$emit('focusComment', {{ $post->id }})"
                        >
                            <x-flux::icon icon="chat-bubble-left" class="w-5 h-5 mr-2" />
                            <span>Comentar</span>
                        </button>
                    </div>
                </div>
            @endforeach

            <!-- Paginação -->
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
