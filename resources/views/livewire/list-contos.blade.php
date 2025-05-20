<div>
    <!-- Category Filter -->
    <div class="mb-6">
        <flux:radio.group wire:model.live="selectedCategory" label="Filtrar por categoria" variant="segmented">
            <flux:radio value="">Todas as categorias</flux:radio>
            @foreach($categories as $category)
                <flux:radio value="{{ $category->id }}">{{ $category->title }}</flux:radio>
            @endforeach
        </flux:radio.group>
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
                                    <a href="{{ route('user.profile', $conto->user->username) }}">
                                        <img src="{{ $conto->user->userPhotos->first() ? asset($conto->user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}"
                                             class="w-10 h-10 rounded-full">
                                    </a>
                                    <div>
                                        <a href="{{ route('user.profile', $conto->user->username) }}" class="font-semibold hover:underline text-gray-300">
                                            {{ $conto->user->name }}
                                        </a>
                                        <div class="text-sm text-gray-200">
                                            {{ '@' . $conto->user->username }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-300">
                                {{ $conto->category->name }}
                            </div>
                        </div>

                        <div class="break-inside-avoid-page mt-2">
                            <h3 class="text-lg font-semibold p-1">
                                <a href="{{ route('contos.show', $conto->id) }}" class="hover:text-sky-600 hover:underline text-gray-300">
                                    {{ $conto->title }}
                                </a>
                            </h3>
                            <div class="prose dark:prose-invert max-w-none text-gray-300">
                                {!! Str::limit(strip_tags($conto->content), 100) !!}
                            </div>
                            <div class="flex justify-betweem-items-center gap-3">
                                <a href="{{ route('contos.show', $conto->id) }}" class="text-sm font-medium text-white bg-blue-500 rounded hover:bg-blue-600 transition p-2">
                                    Ler mais
                                </a>
                                @if(auth()->check() && auth()->id() === $conto->user_id)
                                    <a href="{{ route('contos.edit', $conto->id) }}" class="text-sm font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600 transition p-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-4.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('contos.destroy', $conto->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-white bg-red-500 rounded hover:bg-red-600 transition p-2 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
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
