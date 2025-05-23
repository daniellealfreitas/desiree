<div class="p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="store" enctype="multipart/form-data">
        <textarea wire:model.defer="content" rows="3"
            class="w-full p-3 border border-gray-500 rounded-lg text-gray-300"
            placeholder="Compartilhe o que você pensa com fotos ou vídeos..."></textarea>

        @if ($image)
            <div class="mt-2">
                <img src="{{ $image->temporaryUrl() }}"
                     class="max-w-xs h-auto rounded-lg shadow-sm"
                     alt="Preview">
            </div>
        @endif
        @if ($video)
            <div class="mt-2">
                <video controls class="max-w-xs h-auto rounded-lg shadow-sm">
                    <source src="{{ $video->temporaryUrl() }}" type="video/mp4">
                    Seu navegador não suporta o elemento de vídeo.
                </video>
            </div>
        @endif

        <div class="flex justify-between mt-3">
            <div class="flex space-x-4">
                <label for="image" class="cursor-pointer flex items-center text-gray-500">
                    <x-flux::icon icon="photo" variant="{{ $image ? 'solid' : 'outline' }}" class="w-5 h-5 mr-1" />
                    <input wire:model="image" id="image" type="file" accept="image/*" class="hidden">
                </label>
                @if ($image)
                    <span class="text-sm text-gray-500">
                        @if(is_object($image) && method_exists($image, 'getClientOriginalName'))
                            {{ $image->getClientOriginalName() }}
                        @else
                            Imagem selecionada
                        @endif
                    </span>
                @endif

                <label for="video" class="cursor-pointer flex items-center text-gray-500">
                    <x-flux::icon icon="video-camera" variant="{{ $video ? 'solid' : 'outline' }}" class="w-5 h-5 mr-1" />
                    <input wire:model="video" id="video" type="file" accept="video/*" class="hidden">
                </label>
                @if ($video)
                    <span class="text-sm text-gray-500">
                        @if(is_object($video) && method_exists($video, 'getClientOriginalName'))
                            {{ $video->getClientOriginalName() }}
                        @else
                            Vídeo selecionado
                        @endif
                    </span>
                @endif
            </div>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg disabled:opacity-50"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50">
                <span wire:loading.remove>Postar</span>
                <span wire:loading>Enviando...</span>
            </button>
        </div>

        <div wire:loading wire:target="image,video" class="mt-2">
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div class="bg-purple-600  h-2.5 rounded-full" style="width: 100%"></div>
            </div>
            <div class="text-sm text-gray-500 mt-1">Carregando arquivo...</div>
        </div>
    </form>
</div>
