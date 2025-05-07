<div>
    <h2 class="text-lg font-medium mb-4">Exemplo de Upload de Arquivo</h2>

    <form wire:submit.prevent="save">
        <!-- Exemplo de upload de imagem única -->
        <div class="mb-4">
            <x-file-upload
                wire:model="image"
                label="Imagem"
                accept="image/*"
                icon="photo"
                :iconVariant="$image ? 'solid' : 'outline'"
            />
            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            
            @if($image)
                <div class="mt-2">
                    <img src="{{ $image->temporaryUrl() }}" class="h-32 w-auto rounded" alt="Preview">
                </div>
            @endif
        </div>

        <!-- Exemplo de upload de múltiplos arquivos -->
        <div class="mb-4">
            <x-file-upload
                wire:model="documents"
                label="Documentos"
                accept=".pdf,.doc,.docx"
                multiple
                icon="document-text"
            />
            @error('documents.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Exemplo de upload de vídeo -->
        <div class="mb-4">
            <x-file-upload
                wire:model="video"
                label="Vídeo"
                accept="video/*"
                icon="video-camera"
                :iconVariant="$video ? 'solid' : 'outline'"
            />
            @error('video') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            
            @if($video)
                <div class="mt-2">
                    <video controls class="h-32 w-auto rounded">
                        <source src="{{ $video->temporaryUrl() }}" type="video/mp4">
                        Seu navegador não suporta o elemento de vídeo.
                    </video>
                </div>
            @endif
        </div>

        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">
            <span wire:loading.remove wire:target="save">Salvar</span>
            <span wire:loading wire:target="save">Salvando...</span>
        </button>
    </form>
</div>
