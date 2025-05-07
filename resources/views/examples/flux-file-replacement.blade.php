<div>
    <h2 class="text-lg font-medium mb-4">Substituição do flux::file</h2>

    <div class="mb-6">
        <h3 class="text-md font-medium mb-2">Antes (com flux::file)</h3>
        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
            <pre class="text-sm">
&lt;flux:file
    wire:model="image"
    label="Imagem Principal"
    accept="image/*"
/&gt;
            </pre>
        </div>
    </div>

    <div class="mb-6">
        <h3 class="text-md font-medium mb-2">Depois (com x-file-upload)</h3>
        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
            <pre class="text-sm">
&lt;x-file-upload
    wire:model="image"
    label="Imagem Principal"
    accept="image/*"
    icon="photo"
    :iconVariant="$image ? 'solid' : 'outline'"
/&gt;
            </pre>
        </div>
    </div>

    <div class="mb-6">
        <h3 class="text-md font-medium mb-2">Exemplo com múltiplos arquivos</h3>
        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
            <pre class="text-sm">
&lt;x-file-upload
    wire:model="additionalImages"
    label="Imagens Adicionais"
    accept="image/*"
    multiple
    icon="photo"
/&gt;
            </pre>
        </div>
    </div>

    <div class="mb-6">
        <h3 class="text-md font-medium mb-2">Exemplo com vídeo</h3>
        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
            <pre class="text-sm">
&lt;x-file-upload
    wire:model="video"
    label="Vídeo"
    accept="video/*"
    icon="video-camera"
    :iconVariant="$video ? 'solid' : 'outline'"
/&gt;
            </pre>
        </div>
    </div>
</div>
