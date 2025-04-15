<div>
    <!-- Category Filter -->
    <div class="mb-6">
        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Filtrar por categoria</label>
        <select wire:model.live="selectedCategory" id="category" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm rounded-md dark:bg-zinc-800 dark:border-zinc-700">
            <option value="">Todas as categorias</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Contos Grid -->
    <div class="md:columns-2 lg:columns-3 gap-6 p-4 sm:p-1">
        @forelse($contos as $conto)
            <div class="animate-in zoom-in duration-200">
                <div class="ring-1 rounded-lg flex flex-col space-y-2 p-4 break-inside-avoid mb-6 hover:ring-2 ring-gray-300 hover:ring-sky-400 transform duration-200 hover:shadow-sky-200 hover:shadow-md z-0 relative">
                    <div class="flex flex-col break-inside-avoid-page z-0 relative">
                        <div class="flex justify-between">
                            <div class="flex space-x-6">
                                <div class="flex space-x-4 flex-shrink-0 w-52">
                                    <img src="{{ $conto->user->userPhotos->first() ? asset($conto->user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                         class="w-10 h-10 rounded-full">
                                    <div>
                                        <div class="font-semibold">
                                            {{ $conto->user->name }}
                                        </div>
                                        <div class="text-sm">
                                            {{ '@' . $conto->user->username }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $conto->category->name }}
                            </div>
                        </div>

                        <a href="#" class="whitespace-pre-line break-inside-avoid-page mt-2">
                            <h3 class="text-lg font-semibold mb-2">{{ $conto->title }}</h3>
                            <div class="prose dark:prose-invert max-w-none">
                                {!! Str::limit(strip_tags($conto->content), 200) !!}
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 col-span-full">
                <p class="text-gray-500 dark:text-gray-400">Nenhum conto encontrado.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $contos->links() }}
    </div>
</div>