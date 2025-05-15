<div>
    @if($showModal)
        <flux:modal wire:model="showModal" size="xl">
            <flux:modal.header>
                <flux:heading size="sm">{{ $modalTitle }}</flux:heading>
            </flux:modal.header>

            <flux:modal.body>
                <div class="space-y-4">
                    @if($tempImagePath)
                        <div 
                            x-data="{
                                cropper: null,
                                init() {
                                    // Inicializar o cropper quando o componente for montado
                                    this.$nextTick(() => {
                                        const image = this.$refs.cropperImage;
                                        
                                        if (image) {
                                            this.cropper = new Cropper(image, {
                                                aspectRatio: {{ $aspectRatio }},
                                                viewMode: 1,
                                                dragMode: 'move',
                                                autoCropArea: 1,
                                                responsive: true,
                                                restore: false,
                                                guides: true,
                                                center: true,
                                                highlight: false,
                                                cropBoxMovable: true,
                                                cropBoxResizable: true,
                                                toggleDragModeOnDblclick: false,
                                                minContainerWidth: 600,
                                                minContainerHeight: 400,
                                                crop: (event) => {
                                                    // Atualizar os dados de recorte no componente Livewire
                                                    @this.cropData = {
                                                        x: Math.round(event.detail.x),
                                                        y: Math.round(event.detail.y),
                                                        width: Math.round(event.detail.width),
                                                        height: Math.round(event.detail.height)
                                                    };
                                                }
                                            });
                                        }
                                    });
                                },
                                // Limpar o cropper quando o componente for destruído
                                destroy() {
                                    if (this.cropper) {
                                        this.cropper.destroy();
                                        this.cropper = null;
                                    }
                                }
                            }"
                            x-init="init"
                            x-on:hidden.window="destroy"
                            class="w-full"
                        >
                            <div class="relative w-full h-[400px] bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                <img 
                                    x-ref="cropperImage" 
                                    src="{{ Storage::url($tempImagePath) }}" 
                                    class="max-w-full"
                                    alt="Imagem para recorte"
                                />
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <p>Arraste a imagem para posicionar e ajuste o tamanho da área de recorte conforme necessário.</p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <x-flux::icon name="photo" class="w-12 h-12 mx-auto text-gray-400" />
                            <p class="mt-2 text-gray-500">Nenhuma imagem selecionada para recorte</p>
                        </div>
                    @endif
                </div>
            </flux:modal.body>

            <flux:modal.footer>
                <div class="flex justify-end space-x-3">
                    <x-flux.button wire:click="cancelCropping" variant="secondary">Cancelar</x-flux.button>
                    <x-flux.button wire:click="saveCroppedImage" variant="primary">Salvar</x-flux.button>
                </div>
            </flux:modal.footer>
        </flux:modal>
    @endif
</div>
