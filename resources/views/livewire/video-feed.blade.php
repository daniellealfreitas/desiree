<div
    x-data="{
        currentIndex: 0,
        posts: @js($posts),
        isPlaying: true,
        isMuted: false,

        init() {
            this.$watch('currentIndex', (value) => {
                this.pauseAllVideos();
                this.playCurrentVideo();
            });

            this.$nextTick(() => {
                this.playCurrentVideo();
                this.setupIntersectionObserver();
            });

            // Escutar eventos do Livewire
            Livewire.on('postLiked', (data) => {
                const postIndex = this.posts.findIndex(post => post.id === data.postId);
                if (postIndex !== -1) {
                    this.posts[postIndex].likes_count = data.likesCount;
                    this.posts[postIndex].liked_by_user = data.isLiked;
                }
            });

            Livewire.on('commentAdded', (data) => {
                const postIndex = this.posts.findIndex(post => post.id === data.postId);
                if (postIndex !== -1) {
                    // Adicionar o novo comentário no início da lista
                    this.posts[postIndex].comments.unshift(data.comment);
                    // Atualizar o contador de comentários
                    this.posts[postIndex].comments_count = data.commentsCount;
                }
            });
        },

        setupIntersectionObserver() {
            const videos = document.querySelectorAll('.reel-video');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const index = parseInt(entry.target.dataset.index);
                        if (this.currentIndex !== index) {
                            this.currentIndex = index;
                        }
                    }
                });
            }, { threshold: 0.7 });

            videos.forEach(video => observer.observe(video));
        },

        playCurrentVideo() {
            setTimeout(() => {
                const video = document.querySelector(`.reel-video[data-index='${this.currentIndex}']`);
                if (video) {
                    video.muted = this.isMuted;
                    video.play().catch(e => console.log('Auto-play prevented:', e));
                    this.isPlaying = true;
                }
            }, 100);
        },

        pauseAllVideos() {
            document.querySelectorAll('.reel-video').forEach(video => {
                video.pause();
            });
        },

        togglePlay(index) {
            const video = document.querySelector(`.reel-video[data-index='${index}']`);
            if (video) {
                if (this.isPlaying) {
                    video.pause();
                } else {
                    video.play();
                }
                this.isPlaying = !this.isPlaying;
            }
        },

        toggleMute() {
            this.isMuted = !this.isMuted;
            const video = document.querySelector(`.reel-video[data-index='${this.currentIndex}']`);
            if (video) {
                video.muted = this.isMuted;
            }
        },

        nextVideo() {
            if (this.currentIndex < this.posts.length - 1) {
                this.currentIndex++;
                this.scrollToVideo(this.currentIndex);
            }
        },

        prevVideo() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.scrollToVideo(this.currentIndex);
            }
        },

        scrollToVideo(index) {
            const video = document.querySelector(`.reel-video[data-index='${index}']`);
            if (video) {
                video.scrollIntoView({ behavior: 'smooth' });
            }
        }
    }"
    class="h-screen w-full bg-black overflow-hidden"
    @keydown.arrow-down.window="nextVideo()"
    @keydown.arrow-up.window="prevVideo()"
    @keydown.space.window.prevent="togglePlay(currentIndex)"
    @wheel.debounce.300="$event.deltaY > 0 ? nextVideo() : prevVideo()"
>
    <!-- Reels Container -->
    <div class="snap-y snap-mandatory h-screen w-full overflow-y-scroll">
        <template x-for="(post, index) in posts" :key="post.id">
            <div
                class="snap-start h-screen w-full relative flex items-center justify-center bg-black"
                :id="'reel-' + post.id"
            >
                <!-- Video -->
                <video
                    :src="post.video"
                    class="reel-video h-full w-full object-contain"
                    :data-index="index"
                    loop
                    playsinline
                    @click="togglePlay(index)"
                ></video>

                <!-- Overlay para controles e informações -->
                <div class="absolute inset-0 pointer-events-none">
                    <!-- Gradiente para melhor legibilidade -->
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black opacity-70"></div>

                    <!-- Informações do usuário e descrição -->
                    <div class="absolute bottom-20 left-4 right-16 pointer-events-auto">
                        <div class="flex items-center mb-2">
                            <img :src="post.user.avatar" class="w-10 h-10 rounded-full object-cover border-2 border-white" :alt="post.user.name">
                            <div class="ml-2">
                                <p class="text-white font-bold" x-text="post.user.name"></p>
                                <p class="text-gray-300 text-sm" x-text="'@' + post.user.username"></p>
                            </div>
                        </div>
                        <p class="text-white text-sm mb-2" x-text="post.title"></p>
                        <p class="text-gray-300 text-xs" x-text="post.content"></p>

                        <!-- Seção de comentários (visível apenas quando showComments é true) -->
                        <div
                            x-show="post.showComments"
                            class="mt-4 bg-black bg-opacity-50 rounded-lg p-4 pointer-events-auto"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                        >
                            <!-- Formulário de comentário -->
                            <form
                                x-data="{ commentText: '', isSubmitting: false }"
                                @submit.prevent="
                                    if (commentText.trim() === '') return;
                                    isSubmitting = true;

                                    // Simular adição imediata do comentário para feedback visual
                                    const tempComment = {
                                        id: 'temp-' + Date.now(),
                                        body: commentText,
                                        created_at: 'Agora mesmo',
                                        user: {
                                            id: {{ auth()->id() ?? 0 }},
                                            name: '{{ auth()->user()->name ?? 'Usuário' }}',
                                            username: '{{ auth()->user()->username ?? 'usuario' }}',
                                            avatar: '{{ auth()->user()->userPhotos->first() ? asset('storage/' . auth()->user()->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}'
                                        }
                                    };

                                    // Adicionar temporariamente à lista de comentários
                                    post.comments.unshift(tempComment);
                                    post.comments_count++;

                                    // Enviar para o servidor
                                    $wire.addComment(post.id, commentText)
                                        .then(() => {
                                            commentText = '';
                                            isSubmitting = false;
                                        });
                                "
                                class="flex gap-2 mb-4"
                            >
                                <input
                                    x-model="commentText"
                                    type="text"
                                    class="flex-1 p-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                                    placeholder="Escreva um comentário..."
                                    :disabled="isSubmitting"
                                >
                                <button
                                    type="submit"
                                    class="flex items-center space-x-1 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="isSubmitting || commentText.trim() === ''"
                                >
                                    <x-flux::icon
                                        icon="paper-airplane"
                                        variant="solid"
                                        class="w-4 h-4"
                                    />
                                </button>
                            </form>

                            <!-- Lista de comentários -->
                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                <template x-for="(comment, commentIndex) in post.comments" :key="comment.id">
                                    <div
                                        class="flex items-start space-x-3 p-3 bg-zinc-800 rounded-lg"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        :class="{'border-l-2 border-blue-500': comment.id.toString().includes('temp-')}"
                                    >
                                        <img :src="comment.user.avatar" class="w-8 h-8 rounded-full object-cover">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="font-semibold text-gray-300" x-text="comment.user.username"></p>
                                                <p class="text-gray-500 text-xs" x-text="comment.created_at"></p>
                                            </div>
                                            <p class="text-gray-100" x-text="comment.body"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de ação -->
                    <div class="absolute bottom-20 right-4 flex flex-col items-center space-y-6 pointer-events-auto">
                        <!-- Botão de like -->
                        <div>
                            <button
                                @click.stop="
                                    // Atualizar imediatamente para feedback visual instantâneo
                                    post.liked_by_user = !post.liked_by_user;
                                    post.likes_count += post.liked_by_user ? 1 : -1;
                                    // Chamar o método do Livewire para persistir a mudança
                                    $wire.likePost(post.id);
                                "
                                class="flex flex-col items-center"
                            >
                                <div class="relative">
                                    <x-flux::icon
                                        icon="heart"
                                        variant="outline"
                                        class="w-8 h-8 text-white"
                                        x-show="!post.liked_by_user"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-90"
                                        x-transition:enter-end="opacity-100 scale-100"
                                    />
                                    <x-flux::icon
                                        icon="heart"
                                        variant="solid"
                                        class="w-8 h-8 text-red-500"
                                        x-show="post.liked_by_user"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-90"
                                        x-transition:enter-end="opacity-100 scale-100"
                                    />
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-white text-xs mt-1" x-text="post.likes_count"></span>
                                    <span class="text-white text-xs" x-text="post.liked_by_user ? 'Curtido' : 'Curtir'"></span>
                                </div>
                            </button>
                        </div>

                        <!-- Botão de comentários -->
                        <div>
                            <button
                                @click.stop="
                                    post.showComments = !post.showComments;
                                    if (post.showComments) {
                                        // Focar no campo de comentário quando abrir
                                        setTimeout(() => {
                                            const input = document.querySelector(`#reel-${post.id} form input`);
                                            if (input) input.focus();
                                        }, 300);
                                    }
                                "
                                class="flex flex-col items-center"
                            >
                                <div class="relative">
                                    <x-flux::icon
                                        icon="chat-bubble-left"
                                        variant="outline"
                                        class="w-8 h-8 text-white"
                                        x-show="!post.showComments"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-90"
                                        x-transition:enter-end="opacity-100 scale-100"
                                    />
                                    <x-flux::icon
                                        icon="chat-bubble-left"
                                        variant="solid"
                                        class="w-8 h-8 text-blue-500"
                                        x-show="post.showComments"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-90"
                                        x-transition:enter-end="opacity-100 scale-100"
                                    />
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-white text-xs mt-1" x-text="post.comments_count"></span>
                                    <span class="text-white text-xs" x-text="post.showComments ? 'Comentando' : 'Comentar'"></span>
                                </div>
                            </button>
                        </div>

                        <!-- Botão de compartilhar -->
                        <div>
                            <button class="flex flex-col items-center">
                                <x-flux::icon
                                    icon="share"
                                    variant="outline"
                                    class="w-8 h-8 text-white"
                                />
                                <span class="text-white text-xs mt-1">Compartilhar</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Controles de navegação -->
                <button
                    @click.stop="prevVideo()"
                    class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-black bg-opacity-50 rounded-full p-2"
                    x-show="index > 0"
                >
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <button
                    @click.stop="nextVideo()"
                    class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-black bg-opacity-50 rounded-full p-2"
                    x-show="index < posts.length - 1"
                >
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Indicador de reprodução/pausa -->
                <div
                    class="absolute inset-0 flex items-center justify-center pointer-events-none"
                    x-show="!isPlaying"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                >
                    <div class="bg-black bg-opacity-50 rounded-full p-4">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Botão de mudo/som -->
                <button
                    @click.stop="toggleMute()"
                    class="absolute top-4 right-4 bg-black bg-opacity-50 rounded-full p-2"
                >
                    <svg
                        class="w-6 h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        x-show="!isMuted"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                    </svg>
                    <svg
                        class="w-6 h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        x-show="isMuted"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path>
                    </svg>
                </button>

                <!-- Indicador de progresso -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-800">
                    <div
                        class="h-full bg-white"
                        :style="{ width: '0%' }"
                        x-ref="progressBar"
                        x-init="
                            setInterval(() => {
                                const video = document.querySelector(`.reel-video[data-index='${index}']`);
                                if (video && !video.paused) {
                                    const progress = (video.currentTime / video.duration) * 100;
                                    $refs.progressBar.style.width = `${progress}%`;
                                }
                            }, 100)
                        "
                    ></div>
                </div>
            </div>
        </template>
    </div>
</div>
