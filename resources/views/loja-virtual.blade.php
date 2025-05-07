<x-layouts.app :title="__('Loja')">
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Banner da Loja -->
            <div class="relative overflow-hidden rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-16 sm:px-12 sm:py-24 mb-8">
                <div class="relative">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">Loja Virtual</h1>
                        <p class="mx-auto mt-6 max-w-2xl text-xl text-indigo-100">Encontre produtos exclusivos para membros do clube.</p>
                        <div class="mt-10 flex justify-center gap-x-6">
                            <flux:button :href="route('shop.index')" >
                                Ver Produtos
                            </flux:button>
                            @auth
                                <flux:button :href="route('shop.cart')" >
                                    <flux:icon name="shopping-cart" class="h-5 w-5 mr-2" />
                                    Carrinho
                                </flux:button>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                    <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
            </div>

            <!-- Categorias em Destaque -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mb-6">Categorias em Destaque</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <a href="{{ route('shop.index') }}" class="group relative overflow-hidden rounded-lg bg-gray-100 dark:bg-zinc-900">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/30 to-indigo-600/30 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative aspect-[16/9] w-full bg-gray-200 dark:bg-zinc-800 sm:aspect-[2/1] lg:aspect-[3/2]">
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" alt="Produtos" class="h-full w-full object-cover object-center">
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-white">Produtos</h3>
                                <span class="mt-2 inline-block rounded-full bg-white/20 px-3 py-1 text-sm font-medium text-white backdrop-blur-sm">Ver todos</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('shop.index') }}?showOnlyOnSale=1" class="group relative overflow-hidden rounded-lg bg-gray-100 dark:bg-zinc-900">
                        <div class="absolute inset-0 bg-gradient-to-br from-red-600/30 to-orange-600/30 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative aspect-[16/9] w-full bg-gray-200 dark:bg-zinc-800 sm:aspect-[2/1] lg:aspect-[3/2]">
                            <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da" alt="Promoções" class="h-full w-full object-cover object-center">
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-white">Promoções</h3>
                                <span class="mt-2 inline-block rounded-full bg-white/20 px-3 py-1 text-sm font-medium text-white backdrop-blur-sm">Ver ofertas</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('shop.index') }}?sortBy=created_at&sortDirection=desc" class="group relative overflow-hidden rounded-lg bg-gray-100 dark:bg-zinc-900">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/30 to-cyan-600/30 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative aspect-[16/9] w-full bg-gray-200 dark:bg-zinc-800 sm:aspect-[2/1] lg:aspect-[3/2]">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff" alt="Novidades" class="h-full w-full object-cover object-center">
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-white">Novidades</h3>
                                <span class="mt-2 inline-block rounded-full bg-white/20 px-3 py-1 text-sm font-medium text-white backdrop-blur-sm">Ver lançamentos</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Produtos em Destaque -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Produtos em Destaque</h2>
                    <flux:button :href="route('shop.index')" >
                        Ver todos
                        <flux:icon name="arrow-right" class="h-4 w-4 ml-1" />
                    </flux:button>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    <!-- Produto 1 -->
                    <div class="group relative">
                        <div class="aspect-square w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:h-80">
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" alt="Produto 1" class="h-full w-full object-cover object-center">
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700 dark:text-gray-300">
                                    <a href="{{ route('shop.index') }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        Produto Premium
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Exclusivo</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">R$ 199,90</p>
                        </div>
                    </div>

                    <!-- Produto 2 -->
                    <div class="group relative">
                        <div class="aspect-square w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:h-80">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff" alt="Produto 2" class="h-full w-full object-cover object-center">
                        </div>
                        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                            OFERTA
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700 dark:text-gray-300">
                                    <a href="{{ route('shop.index') }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        Tênis Esportivo
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Vermelho</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">R$ 149,90</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-through">R$ 199,90</p>
                            </div>
                        </div>
                    </div>

                    <!-- Produto 3 -->
                    <div class="group relative">
                        <div class="aspect-square w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:h-80">
                            <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da" alt="Produto 3" class="h-full w-full object-cover object-center">
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700 dark:text-gray-300">
                                    <a href="{{ route('shop.index') }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        Relógio Elegante
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Preto</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">R$ 299,90</p>
                        </div>
                    </div>

                    <!-- Produto 4 -->
                    <div class="group relative">
                        <div class="aspect-square w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:h-80">
                            <img src="https://images.unsplash.com/photo-1585386959984-a4155224a1ad" alt="Produto 4" class="h-full w-full object-cover object-center">
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700 dark:text-gray-300">
                                    <a href="{{ route('shop.index') }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        Perfume Luxuoso
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Unissex</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">R$ 249,90</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner de Promoção -->
            <div class="mt-16 rounded-lg bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500 p-1">
                <div class="rounded-lg bg-white dark:bg-zinc-800 px-6 py-8 sm:p-10 sm:pb-14">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-1 text-center lg:text-left">
                            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                                Promoção Especial para Membros VIP
                            </h2>
                            <p class="mt-3 text-lg text-gray-500 dark:text-gray-400">
                                Ganhe 20% de desconto em todos os produtos da loja usando o cupom <span class="font-bold text-pink-600 dark:text-pink-400">VIPCLUB</span>
                            </p>
                        </div>
                        <div class="mt-6 w-full flex-none text-center lg:mt-0 lg:w-auto">
                            <flux:button :href="route('shop.index')">
                                Aproveitar Agora
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>