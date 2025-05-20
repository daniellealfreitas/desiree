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
    public static $name = 'settings.profile-with-cover';
    use WithFileUploads;

    /**
     * The cover photo file upload.
     *
     * @var \Livewire\TemporaryUploadedFile|null
     */
    public $cover = null;

    /**
     * Whether to show the cropper interface.
     *
     * @var bool
     */
    public $showCropper = false;

    /**
     * The crop data from the cropper.
     *
     * @var array
     */
    public $cropData = [
        'x' => 0,
        'y' => 0,
        'width' => 0,
        'height' => 0
    ];

    /**
     * The temporary URL for the uploaded image.
     *
     * @var string|null
     */
    public $tempImageUrl = null;

    /**
     * Processing state for UI feedback.
     *
     * @var bool
     */
    public $processing = false;

    /**
     * Validation rules for the cover photo.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'cover' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:10240'], // 10MB max
        ];
    }

    /**
     * Update the user's cover photo.
     *
     * @return void
     */
    public function updateCover(): void
    {
        try {
            $this->processing = true;

            $user = Auth::user();

            if (!$user) {
                throw new \Exception('Authenticated user not found.');
            }

            $this->validate();

            if ($this->cover) {
                // Log para debug
                Log::info('Iniciando processo de atualização da capa', [
                    'user_id' => $user->id
                ]);

                // Store the file and get the path
                $coverPath = $this->cover->store('covers', 'public');

                if (!$coverPath) {
                    throw new \Exception('Failed to store the cover photo.');
                }

                Log::info('Arquivo de capa salvo', [
                    'coverPath' => $coverPath
                ]);

                // Process the cropped image
                $croppedPath = $this->processCroppedImage($coverPath);

                // Se o processamento falhar, usamos o caminho original
                if (!$croppedPath) {
                    $croppedPath = $coverPath;
                    Log::warning('Usando imagem original como fallback', [
                        'coverPath' => $coverPath
                    ]);
                }

                // Verificar se já existe uma capa para este usuário
                $existingCover = UserCoverPhoto::where('user_id', $user->id)->latest()->first();

                if ($existingCover) {
                    Log::info('Atualizando capa existente', [
                        'existingCoverId' => $existingCover->id
                    ]);

                    // Atualizar a capa existente
                    $existingCover->update([
                        'photo_path' => $coverPath,
                        'crop_x' => $this->cropData['x'],
                        'crop_y' => $this->cropData['y'],
                        'crop_width' => $this->cropData['width'],
                        'crop_height' => $this->cropData['height'],
                        'cropped_photo_path' => $croppedPath
                    ]);
                } else {
                    // Create the cover photo record with crop data
                    UserCoverPhoto::create([
                        'user_id' => $user->id,
                        'photo_path' => $coverPath,
                        'crop_x' => $this->cropData['x'],
                        'crop_y' => $this->cropData['y'],
                        'crop_width' => $this->cropData['width'],
                        'crop_height' => $this->cropData['height'],
                        'cropped_photo_path' => $croppedPath
                    ]);
                }

                Log::info('Capa atualizada com sucesso', [
                    'user_id' => $user->id,
                    'coverPath' => $coverPath,
                    'croppedPath' => $croppedPath
                ]);

                $this->reset(['cover', 'showCropper', 'tempImageUrl', 'cropData']);
                $this->dispatch('cover-updated');
            }
        } catch (\Throwable $e) {
            Log::error('Error updating cover photo: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            session()->flash('error', 'Ocorreu um erro ao atualizar a capa. Tente novamente.');
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Process the cropped image and save it.
     *
     * @param string $originalPath The path to the original image
     * @return string|null The path to the cropped image or null on failure
     */
    protected function processCroppedImage(string $originalPath): ?string
    {
        try {
            // Validate crop data
            if (empty($this->cropData['width']) || empty($this->cropData['height'])) {
                Log::warning('Invalid or empty crop data', [
                    'cropData' => $this->cropData
                ]);
                // Se não houver dados de recorte válidos, retornar o caminho original
                return $originalPath;
            }

            // Get file paths
            $originalImage = Storage::disk('public')->path($originalPath);
            $croppedPath = 'covers/cropped_' . basename($originalPath);
            $croppedFullPath = Storage::disk('public')->path($croppedPath);

            // Create directory if it doesn't exist
            $directory = dirname($croppedFullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Log para debug
            Log::info('Processando imagem recortada', [
                'originalPath' => $originalPath,
                'croppedPath' => $croppedPath,
                'cropData' => $this->cropData
            ]);

            // Create image manager instance with GD driver
            $manager = new ImageManager(new Driver());

            // Load image
            $image = $manager->read($originalImage);

            // Ensure crop values are valid positive integers
            $cropX = max(0, (int)$this->cropData['x']);
            $cropY = max(0, (int)$this->cropData['y']);
            $cropWidth = max(10, (int)$this->cropData['width']);
            $cropHeight = max(10, (int)$this->cropData['height']);

            // Verificar se os valores de recorte estão dentro dos limites da imagem
            $imageWidth = $image->width();
            $imageHeight = $image->height();

            if ($cropX + $cropWidth > $imageWidth) {
                $cropWidth = $imageWidth - $cropX;
            }

            if ($cropY + $cropHeight > $imageHeight) {
                $cropHeight = $imageHeight - $cropY;
            }

            // Log para debug
            Log::info('Dimensões finais de recorte', [
                'x' => $cropX,
                'y' => $cropY,
                'width' => $cropWidth,
                'height' => $cropHeight,
                'imageWidth' => $imageWidth,
                'imageHeight' => $imageHeight
            ]);

            // Crop the image
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
            // Em caso de erro, retornar o caminho original
            return $originalPath;
        }
    }

    /**
     * Set crop data from JavaScript.
     *
     * @param array $data The crop data
     * @return void
     */
    public function setCropData(array $data): void
    {
        // Verificar se os dados são válidos
        if (isset($data['x']) && isset($data['y']) && isset($data['width']) && isset($data['height'])) {
            // Garantir que todos os valores são inteiros positivos
            $data['x'] = max(0, (int)$data['x']);
            $data['y'] = max(0, (int)$data['y']);
            $data['width'] = max(10, (int)$data['width']);
            $data['height'] = max(10, (int)$data['height']);

            $this->cropData = $data;

            // Log para debug
            Log::info('Dados de recorte atualizados', $data);
        } else {
            Log::warning('Dados de recorte inválidos recebidos', $data);
        }
    }

    /**
     * Handle file upload and prepare for cropping.
     *
     * @return void
     */
    public function updatedCover(): void
    {
        if ($this->cover) {
            $this->tempImageUrl = $this->cover->temporaryUrl();
            $this->showCropper = true;
            $this->dispatch('cover-image-uploaded');
        }
    }

    /**
     * Cancel cropping.
     *
     * @return void
     */
    public function cancelCrop(): void
    {
        $this->reset(['cover', 'showCropper', 'tempImageUrl', 'cropData']);
    }
}; ?>

<section class="w-full profile-with-cover">
    <!-- Garantir que o Cropper.js esteja carregado -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0/cropper.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0/cropper.min.css" rel="stylesheet">

    <!-- Estilos inline para garantir que o Cropper.js funcione corretamente -->
    <style>
        #crop-container {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 500px;
            background-color: #f3f4f6;
            border-radius: 0.5rem;
        }

        #coverImage {
            display: block;
            max-width: 100%;
            max-height: 100%;
        }

        .cropper-container {
            position: relative !important;
            overflow: hidden !important;
            direction: ltr !important;
            touch-action: none !important;
            user-select: none !important;
        }

        .dark #crop-container {
            background-color: #1f2937;
        }
    </style>

    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Cover')" :subheading="__('Update your cover photo')">
        <form wire:submit.prevent="updateCover" class="my-6 w-full space-y-6">
            <div>
                <!-- File upload component -->
                <x-file-upload
                    wire:model.live="cover"
                    label="Foto de Capa"
                    accept="image/png, image/jpeg"
                    icon="photo"
                    :iconVariant="$cover ? 'solid' : 'outline'"
                    help="Selecione uma imagem para usar como capa do seu perfil. Tamanho máximo: 10MB."
                />

                @error('cover')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror

                <!-- Cropper interface -->
                @if($showCropper && $tempImageUrl)
                    <div class="mt-4">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Selecione a área da imagem que deseja usar como capa (arraste e redimensione livremente)
                        </div>

                        <!-- Cropper container -->
                        <div class="relative mb-4 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden" style="height: 500px; width: 100%;" wire:ignore id="crop-container">
                            <img
                                id="coverImage"
                                src="{{ $tempImageUrl }}"
                                alt="Imagem para recorte"
                                style="display: block; max-width: 100%; max-height: 100%;"
                            >
                        </div>

                        <!-- Crop controls -->
                        <div class="flex items-center gap-2 mt-4">
                            <x-flux::button
                                type="button"
                                variant="primary"
                                wire:click="cancelCrop"
                                class="flex-1"
                                :disabled="$processing"
                            >
                                Cancelar
                            </x-flux::button>

                            <x-flux::button
                                type="submit"
                                variant="primary"
                                class="flex-1"
                                :disabled="$processing"
                                wire:loading.attr="disabled"
                                wire:target="updateCover"
                            >
                                <span wire:loading.remove wire:target="updateCover">
                                    Salvar
                                </span>
                                <span wire:loading wire:target="updateCover">
                                    Salvando...
                                </span>
                            </x-flux::button>
                        </div>
                    </div>
                @elseif (auth()->user() && auth()->user()->userCoverPhotos()->latest()->first())
                    @php
                        $coverPhoto = auth()->user()->userCoverPhotos()->latest()->first();
                        $photoPath = $coverPhoto->cropped_photo_path ?? $coverPhoto->photo_path;
                    @endphp

                    <div class="mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Foto de capa atual
                        </p>
                        <img
                            src="{{ Storage::url($photoPath) }}"
                            alt="Capa"
                            class="w-full h-40 rounded-lg object-cover border border-gray-200 dark:border-gray-700"
                        >
                    </div>
                @endif
            </div>

            <!-- Action buttons and messages -->
            <div class="flex items-center gap-4">
                @if(!$showCropper)
                    <div class="flex items-center justify-end">
                        <x-flux::button
                            variant="primary"
                            type="submit"
                            class="w-full"
                            :disabled="!$cover || $processing"
                            wire:loading.attr="disabled"
                            wire:target="updateCover"
                        >
                            <span wire:loading.remove wire:target="updateCover">
                                Salvar
                            </span>
                            <span wire:loading wire:target="updateCover">
                                Salvando...
                            </span>
                        </x-flux::button>
                    </div>
                @endif

                <x-action-message class="me-3" on="cover-updated">
                    Foto de capa atualizada com sucesso.
                </x-action-message>

                @if (session('error'))
                    <p class="mt-2 font-medium text-red-600 dark:text-red-400">
                        {{ session('error') }}
                    </p>
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
                const image = document.getElementById('coverImage');

                if (!image) return;

                // Destruir instância anterior se existir
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }

                // Inicializar nova instância
                cropper = new Cropper(image, {
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    responsive: true,
                    guides: true,
                    center: true,
                    highlight: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    minCropBoxWidth: 100,
                    minCropBoxHeight: 100,
                    ready: function() {
                        // Ajustar área de recorte inicial
                        const canvasData = cropper.getCanvasData();
                        cropper.setCropBoxData({
                            left: canvasData.left + canvasData.width * 0.1,
                            top: canvasData.top + canvasData.height * 0.1,
                            width: canvasData.width * 0.8,
                            height: canvasData.height * 0.8
                        });
                    },
                    crop: function(event) {
                        // Enviar dados de recorte para o componente Livewire
                        const data = {
                            x: Math.round(event.detail.x),
                            y: Math.round(event.detail.y),
                            width: Math.round(event.detail.width),
                            height: Math.round(event.detail.height)
                        };

                        if (data.width > 0 && data.height > 0) {
                            $wire.setCropData(data);
                        }
                    }
                });
            }

            // Inicializar quando a imagem for carregada
            Livewire.on('cover-image-uploaded', function() {
                // Dar tempo para o DOM atualizar
                setTimeout(function() {
                    const image = document.getElementById('coverImage');

                    if (image) {
                        if (image.complete) {
                            initCropper();
                        } else {
                            image.onload = initCropper;
                        }
                    }
                }, 200);
            });

            // Limpar quando o componente for desconectado
            document.addEventListener('livewire:disconnected', function() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });
        });
    </script>
</section>