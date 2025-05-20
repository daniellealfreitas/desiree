<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Depuração de Upload de Arquivo</h2>
    
    <div class="mb-6">
        <form wire:submit.prevent="save">
            <div class="mb-4">
                <x-file-upload
                    wire:model="file"
                    label="Arquivo para teste"
                    accept="*/*"
                    icon="document"
                    :iconVariant="$file ? 'solid' : 'outline'"
                    help="Selecione qualquer arquivo para testar o upload"
                />
                @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                <span wire:loading.remove wire:target="save">Salvar Arquivo</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </form>
    </div>
    
    <div class="mt-6">
        <h3 class="text-lg font-medium mb-2">Status do Upload</h3>
        
        @if($uploadStatus)
            <div class="p-4 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 rounded-lg mb-4">
                {{ $uploadStatus }}
            </div>
        @endif
        
        @if($errorMessage)
            <div class="p-4 bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100 rounded-lg mb-4">
                {{ $errorMessage }}
            </div>
        @endif
        
        <div class="mt-4">
            <h4 class="font-medium mb-2">Informações de Depuração</h4>
            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                <p class="mb-2"><strong>PHP upload_max_filesize:</strong> {{ ini_get('upload_max_filesize') }}</p>
                <p class="mb-2"><strong>PHP post_max_size:</strong> {{ ini_get('post_max_size') }}</p>
                <p class="mb-2"><strong>PHP max_execution_time:</strong> {{ ini_get('max_execution_time') }} segundos</p>
                <p><strong>PHP memory_limit:</strong> {{ ini_get('memory_limit') }}</p>
            </div>
        </div>
    </div>
    
    <div class="mt-6">
        <h3 class="text-lg font-medium mb-2">Console de Eventos</h3>
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg h-32 overflow-y-auto" id="debug-console">
            <p class="text-sm text-gray-500 dark:text-gray-400">Os eventos de upload serão exibidos aqui...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        const console = document.getElementById('debug-console');
        
        function logEvent(message) {
            const time = new Date().toLocaleTimeString();
            const logEntry = document.createElement('p');
            logEntry.className = 'text-sm mb-1';
            logEntry.innerHTML = `<span class="text-gray-500">[${time}]</span> ${message}`;
            console.prepend(logEntry);
        }
        
        // Log all Livewire upload events
        Livewire.hook('upload:start', (upload) => {
            logEvent(`<span class="text-blue-500">Upload iniciado:</span> ${upload.name}`);
        });
        
        Livewire.hook('upload:progress', (upload) => {
            logEvent(`<span class="text-blue-500">Progresso:</span> ${upload.name} - ${upload.progress}%`);
        });
        
        Livewire.hook('upload:finish', (upload) => {
            logEvent(`<span class="text-green-500">Upload concluído:</span> ${upload.name}`);
        });
        
        Livewire.hook('upload:error', (upload, error) => {
            logEvent(`<span class="text-red-500">Erro:</span> ${error}`);
        });
    });
</script>
