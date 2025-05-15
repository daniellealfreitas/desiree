<?php

use App\Models\UserCoverPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

new class extends Component {
    use WithFileUploads;

    public $cover;
    public $showCropper = false;
    public $cropData = [
        'x' => 0,
        'y' => 0,
        'width' => 0,
        'height' => 0
    ];
    public $tempImageUrl = null;

    /**
     * Update the user's cover photo.
     */
    public function updateCover(): void
    {
        try {
            $user = Auth::user();

            if (!$user) {
                throw new \Exception('Authenticated user not found.');
            }

            $validated = $this->validate([
                'cover' => ['required', 'image', 'mimes:jpg,png', 'max:5120'], // Allow JPG/PNG and increase size limit to 5MB
            ]);

            if ($this->cover) {
                // Store the file and get the path
                $coverPath = $this->cover->store('covers', 'public');

                if (!$coverPath) {
                    throw new \Exception('Failed to store the cover photo.');
                }

                // Create the cover photo record with crop data
                UserCoverPhoto::create([
                    'user_id' => $user->id,
                    'photo_path' => $coverPath,
                    'crop_x' => $this->cropData['x'],
                    'crop_y' => $this->cropData['y'],
                    'crop_width' => $this->cropData['width'],
                    'crop_height' => $this->cropData['height'],
                    'cropped_photo_path' => $this->processCroppedImage($coverPath)
                ]);
            }

            $this->reset(['cover', 'showCropper', 'tempImageUrl', 'cropData']);
            $this->dispatch('cover-updated');
        } catch (\Throwable $e) {
            Log::error('Error updating cover photo: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Ocorreu um erro ao atualizar a capa. Tente novamente.');
        }
    }

    /**
     * Process the cropped image and save it
     */
    protected function processCroppedImage($originalPath): ?string
    {
        try {
            // Log para debug
            Log::info('Processando imagem recortada', [
                'originalPath' => $originalPath,
                'cropData' => $this->cropData
            ]);

            if (empty($this->cropData['width']) || empty($this->cropData['height'])) {
                Log::warning('Dados de recorte inválidos ou vazios');
                return null;
            }

            $originalImage = Storage::disk('public')->path($originalPath);
            $croppedPath = 'covers/cropped_' . basename($originalPath);
            $croppedFullPath = Storage::disk('public')->path($croppedPath);

            // Create directory if it doesn't exist
            $directory = dirname($croppedFullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Create image manager instance with GD driver
            $manager = new ImageManager(new Driver());

            // Load image and crop it
            $image = $manager->read($originalImage);

            // Garantir que os valores são inteiros positivos
            $cropX = max(0, (int)$this->cropData['x']);
            $cropY = max(0, (int)$this->cropData['y']);
            $cropWidth = max(10, (int)$this->cropData['width']);
            $cropHeight = max(10, (int)$this->cropData['height']);

            Log::info('Recortando imagem com dimensões', [
                'x' => $cropX,
                'y' => $cropY,
                'width' => $cropWidth,
                'height' => $cropHeight
            ]);

            $image->crop(
                $cropWidth,
                $cropHeight,
                $cropX,
                $cropY
            );

            // Save the cropped image
            $image->save($croppedFullPath);

            Log::info('Imagem recortada salva com sucesso', [
                'path' => $croppedPath
            ]);

            return $croppedPath;
        } catch (\Throwable $e) {
            Log::error('Error processing cropped image: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Set crop data from JavaScript
     */
    public function setCropData($data): void
    {
        $this->cropData = $data;
        Log::info('Dados do recorte recebidos', $data);
    }

    /**
     * Handle file upload and prepare for cropping
     */
    public function updatedCover(): void
    {
        if ($this->cover) {
            $this->tempImageUrl = $this->cover->temporaryUrl();
            $this->showCropper = true;

            // Dispatch event to initialize cropper
            $this->dispatch('updatedCover');

            // Adicionar um log para debug
            Log::info('Cover atualizado, showCropper = ' . ($this->showCropper ? 'true' : 'false'));
        }
    }

    /**
     * Cancel cropping
     */
    public function cancelCrop(): void
    {
        $this->reset(['cover', 'showCropper', 'tempImageUrl', 'cropData']);
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Capa')" :subheading="__('Atualizar sua foto de capa')">
        <form wire:submit.prevent="updateCover" class="my-6 w-full space-y-6">
            <div>
                <x-file-upload wire:model.live="cover" :label="__('Capa')" accept="image/png, image/jpeg" icon="photo" :iconVariant="$cover ? 'solid' : 'outline'" />

                @if($showCropper && $tempImageUrl)
                    <div class="mt-4">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Selecione a área da imagem que deseja usar como capa
                        </div>

                        <!-- Cropper container -->
                        <div class="cropper-container-wrapper relative mb-4" style="height: 500px !important; width: 100%; overflow: hidden; background-color: #f8f8f8;" wire:ignore>
                            <img id="coverImage" src="{{ $tempImageUrl }}" alt="Imagem para recorte" style="max-width: 100%; min-height: 500px; height: auto; display: block;">
                        </div>

                        <script>
                            // Inicializar o cropper diretamente quando a imagem for carregada
                            document.addEventListener('DOMContentLoaded', function() {
                                setTimeout(function() {
                                    const image = document.getElementById('coverImage');
                                    if (image && typeof Cropper !== 'undefined') {
                                        console.log('Inicializando cropper diretamente...');
                                        new Cropper(image, {
                                            viewMode: 0, // Sem restrições
                                            dragMode: 'move',
                                            aspectRatio: 16 / 5,
                                            autoCropArea: 1, // Usar toda a área
                                            responsive: false, // Desativar responsividade
                                            restore: false,
                                            guides: true,
                                            center: true,
                                            highlight: true,
                                            cropBoxMovable: true,
                                            cropBoxResizable: true,
                                            toggleDragModeOnDblclick: false,
                                            minContainerWidth: 800,
                                            minContainerHeight: 500,
                                            minCropBoxWidth: 800,
                                            minCropBoxHeight: 500,
                                            minCanvasWidth: 800,
                                            minCanvasHeight: 500,
                                            ready: function() {
                                                console.log('Cropper direto está pronto');

                                                // FORÇAR altura do canvas para exatamente 500px
                                                console.log('Forçando altura do canvas para 500px');

                                                // Obter dados do container
                                                const containerData = this.cropper.getContainerData();

                                                // Definir altura fixa de 500px
                                                const fixedHeight = 500;

                                                // Calcular largura mantendo proporção 16:5
                                                const fixedWidth = fixedHeight * (16/5);

                                                // Centralizar o canvas horizontalmente
                                                const left = (containerData.width - fixedWidth) / 2;
                                                const top = 0; // Alinhar ao topo

                                                console.log('Dimensões do canvas:', {
                                                    width: fixedWidth,
                                                    height: fixedHeight,
                                                    left: left,
                                                    top: top
                                                });

                                                // Aplicar o tamanho fixo ao canvas
                                                this.cropper.setCanvasData({
                                                    left: left,
                                                    top: top,
                                                    width: fixedWidth,
                                                    height: fixedHeight
                                                });

                                                // Ajustar a caixa de recorte para ter o mesmo tamanho
                                                this.cropper.setCropBoxData({
                                                    left: left,
                                                    top: top,
                                                    width: fixedWidth,
                                                    height: fixedHeight
                                                });
                                            },
                                            crop: function(event) {
                                                // Enviar dados do recorte para o componente Livewire
                                                const data = {
                                                    x: Math.round(event.detail.x),
                                                    y: Math.round(event.detail.y),
                                                    width: Math.round(event.detail.width),
                                                    height: Math.round(event.detail.height)
                                                };

                                                // Encontrar o componente Livewire
                                                const livewireEl = document.querySelector('[wire\\:id]');
                                                if (livewireEl && livewireEl.__livewire) {
                                                    Livewire.find(livewireEl.__livewire.$id).setCropData(data);
                                                }
                                            }
                                        });
                                    }
                                }, 500);
                            });
                        </script>

                        <!-- Crop controls -->
                        <div class="flex items-center gap-2 mt-2">
                            <flux:button type="button" variant="primary" wire:click="cancelCrop" class="flex-1">
                                Cancelar
                            </flux:button>
                            <flux:button type="submit" variant="primary" class="flex-1">
                                Salvar
                            </flux:button>
                        </div>
                    </div>
                @elseif (auth()->user() && auth()->user()->userCoverPhotos()->latest()->first())
                    @php
                        $coverPhoto = auth()->user()->userCoverPhotos()->latest()->first();
                        $photoPath = $coverPhoto->cropped_photo_path ?? $coverPhoto->photo_path;
                    @endphp
                    <img src="{{ Storage::url($photoPath) }}" alt="Capa" class="mt-4 w-full h-40 rounded object-cover">
                @endif
            </div>

            <div class="flex items-center gap-4">
                @if(!$showCropper)
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit" class="w-full">Salvar</flux:button>
                    </div>
                @endif

                <x-action-message class="me-3" on="cover-updated">
                    Capa atualizada.
                </x-action-message>

                @if (session('error'))
                    <flux:text class="mt-2 font-medium !dark:text-red-400 !text-red-600">
                        {{ session('error') }}
                    </flux:text>
                @endif
            </div>
        </form>
    </x-settings.layout>

    <!-- Cropper.js initialization script -->
    <script>
        document.addEventListener('livewire:initialized', function() {
            let cropper = null;

            // Função para inicializar o cropper
            function initCropper() {
                console.log('Inicializando cropper...');
                const image = document.getElementById('coverImage');
                if (!image) {
                    console.log('Imagem não encontrada');
                    return;
                }

                // Garantir que a imagem esteja carregada
                if (!image.complete) {
                    image.onload = function() {
                        setupCropper(image);
                    };
                } else {
                    setupCropper(image);
                }
            }

            // Configurar o cropper na imagem
            function setupCropper(image) {
                console.log('Configurando cropper na imagem');

                // Destruir cropper existente se houver
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }

                // Inicializar Cropper.js com opções mais simples primeiro
                try {
                    cropper = new Cropper(image, {
                        viewMode: 2,
                        dragMode: 'move',
                        aspectRatio: 16 / 5,
                        autoCropArea: 0.9,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: true,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: true,
                        minContainerWidth: 800,
                        minContainerHeight: 500,
                        minCropBoxWidth: 400,
                        minCropBoxHeight: 500,
                        CanvasWidth: 800,
                        CanvasHeight: 500,
                        ready: function() {
                            console.log('Cropper está pronto');

                            // Ajustar o tamanho do canvas quando o cropper estiver pronto
                            const canvas = this.cropper.getCanvasData();
                            const containerData = this.cropper.getContainerData();

                            // Calcular o novo tamanho do canvas para preencher o container
                            const newWidth = containerData.width;
                            const newHeight = 500;

                            // Centralizar o canvas
                            const left = 0;
                            const top = (containerData.height - newHeight) / 2;

                            // Aplicar o novo tamanho
                            this.cropper.setCanvasData({
                                left: left,
                                top: top,
                                width: 800,
                                height: 500
                            });

                            // Ajustar a caixa de recorte para cobrir todo o canvas
                            this.cropper.setCropBoxData({
                                left: 0,
                                top: top,
                                width: newWidth,
                                height: 500
                            });
                        },
                        crop: function(event) {
                            // Enviar dados do recorte para o componente Livewire
                            const data = {
                                x: Math.round(event.detail.x),
                                y: Math.round(event.detail.y),
                                width: Math.round(event.detail.width),
                                height: Math.round(event.detail.height)
                            };
                            console.log('Dados do recorte:', data);

                            // Encontrar o componente Livewire
                            const livewireEl = document.querySelector('[wire\\:id]');
                            if (livewireEl && livewireEl.__livewire) {
                                Livewire.find(livewireEl.__livewire.$id).setCropData(data);
                            }
                        }
                    });
                } catch (error) {
                    console.error('Erro ao inicializar o cropper:', error);
                }
            }

            // Observar mudanças no DOM para detectar quando o cropper deve ser inicializado
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' || mutation.type === 'attributes') {
                        const coverImage = document.getElementById('coverImage');
                        if (coverImage && !cropper) {
                            // Pequeno atraso para garantir que a imagem esteja renderizada
                            setTimeout(initCropper, 100);
                        }
                    }
                });
            });

            // Iniciar observação
            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['src', 'style', 'class']
            });

            // Também inicializar quando o evento updatedCover for disparado
            Livewire.on('updatedCover', () => {
                console.log('Evento updatedCover recebido');
                setTimeout(initCropper, 100);
            });

            // Limpar cropper quando o componente for destruído
            document.addEventListener('livewire:navigating', () => {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }

                // Parar de observar
                observer.disconnect();
            });
        });
    </script>
</section>