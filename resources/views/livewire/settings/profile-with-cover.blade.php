<?php

use App\Models\UserCoverPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

new class extends Component {
    use WithFileUploads;

    public $cover = null;
    public $showCropperModal = false;
    public $tempImagePath = null;
    public $cropData = [
        'x' => 0,
        'y' => 0,
        'width' => 0,
        'height' => 0
    ];

    // Garantir que o modal não seja aberto automaticamente ao carregar a página
    public function mount()
    {
        $this->showCropperModal = false;
        $this->tempImagePath = null;
        $this->cropData = [
            'x' => 0,
            'y' => 0,
            'width' => 0,
            'height' => 0
        ];
    }

    // Observador para abrir o modal automaticamente quando uma imagem for selecionada
    public function updatedCover()
    {
        // Só executa o upload para recorte se uma imagem foi realmente selecionada
        if ($this->cover && !$this->showCropperModal) {
            $this->uploadForCropping();
        }
    }

    /**
     * Upload da imagem para recorte
     */
    public function uploadForCropping(): void
    {
        try {
            $validated = $this->validate([
                'cover' => ['required', 'image', 'mimes:jpg,png', 'max:5120'], // Permite JPG/PNG até 5MB
            ]);

            if ($this->cover) {
                // Armazena o arquivo temporariamente e obtém o caminho
                $this->tempImagePath = $this->cover->store('temp', 'public');

                if (!$this->tempImagePath) {
                    throw new \Exception('Falha ao armazenar a imagem temporária.');
                }

                // Mostra o modal com o cropper
                $this->showCropperModal = true;
            }
        } catch (\Throwable $e) {
            Log::error('Erro ao fazer upload da imagem para recorte: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Ocorreu um erro ao fazer upload da imagem. Tente novamente.');
        }
    }

    /**
     * Salva a imagem recortada
     */
    public function saveCroppedImage(): void
    {
        try {
            $user = Auth::user();

            if (!$user) {
                throw new \Exception('Usuário autenticado não encontrado.');
            }

            // Validar dados de recorte
            if (!$this->tempImagePath) {
                throw new \Exception('Imagem temporária não encontrada.');
            }

            // Se os dados de recorte não estiverem definidos, use valores padrão
            if (!$this->cropData['width'] || !$this->cropData['height']) {
                Log::warning('Dados de recorte não definidos, usando valores padrão', [
                    'user_id' => Auth::id(),
                    'cropData' => $this->cropData,
                ]);

                // Obter dimensões da imagem original
                $tempFullPath = Storage::disk('public')->path($this->tempImagePath);
                $imageSize = getimagesize($tempFullPath);

                if ($imageSize) {
                    $this->cropData = [
                        'x' => 0,
                        'y' => 0,
                        'width' => $imageSize[0],
                        'height' => $imageSize[1]
                    ];
                } else {
                    throw new \Exception('Não foi possível obter as dimensões da imagem.');
                }
            }

            // Obter o caminho completo para a imagem temporária
            $tempFullPath = Storage::disk('public')->path($this->tempImagePath);

            // Criar o caminho final para a imagem original
            $coverPath = 'covers/' . basename($this->tempImagePath);
            Storage::disk('public')->copy($this->tempImagePath, $coverPath);

            // Criar a versão recortada
            $croppedPath = 'covers/cropped_' . basename($this->tempImagePath);

            // Carregar a imagem com Intervention Image
            $image = Image::read($tempFullPath);

            // Recortar a imagem
            $croppedImage = $image->crop(
                $this->cropData['width'],
                $this->cropData['height'],
                $this->cropData['x'],
                $this->cropData['y']
            );

            // Salvar a imagem recortada
            $croppedImage->save(Storage::disk('public')->path($croppedPath));

            // Salvar na tabela user_cover_photos
            UserCoverPhoto::create([
                'user_id' => $user->id,
                'photo_path' => $coverPath,
                'crop_x' => $this->cropData['x'],
                'crop_y' => $this->cropData['y'],
                'crop_width' => $this->cropData['width'],
                'crop_height' => $this->cropData['height'],
                'cropped_photo_path' => $croppedPath,
            ]);

            // Limpar o arquivo temporário
            Storage::disk('public')->delete($this->tempImagePath);

            // Resetar o estado do componente
            $this->reset(['cover', 'showCropperModal', 'tempImagePath', 'cropData']);

            // Disparar evento de atualização
            $this->dispatch('cover-updated');

            // Adicionar mensagem de sucesso
            session()->flash('success', 'Capa atualizada com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao salvar imagem recortada: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Ocorreu um erro ao salvar a imagem. Tente novamente.');
        }
    }

    /**
     * Cancela o processo de recorte
     */
    public function cancelCropping(): void
    {
        // Limpar o arquivo temporário se existir
        if ($this->tempImagePath) {
            Storage::disk('public')->delete($this->tempImagePath);
        }

        // Resetar o estado do componente
        $this->reset(['cover', 'showCropperModal', 'tempImagePath', 'cropData']);
    }

    /**
     * Update the user's cover photo.
     */
    public function updateCover(): void
    {
        // Validar se há uma imagem selecionada
        $this->validate([
            'cover' => ['required', 'image', 'mimes:jpg,png', 'max:5120'],
        ]);

        // Se chegou até aqui, temos uma imagem válida, então podemos iniciar o processo de recorte
        $this->uploadForCropping();
    }

    /**
     * Observador para quando o modal é fechado
     */
    public function updatedShowCropperModal($value)
    {
        if (!$value) {
            // Se o modal foi fechado sem salvar, limpar o arquivo temporário
            if ($this->tempImagePath) {
                Storage::disk('public')->delete($this->tempImagePath);
                $this->tempImagePath = null;
            }

            // Resetar os dados de recorte
            $this->cropData = [
                'x' => 0,
                'y' => 0,
                'width' => 0,
                'height' => 0
            ];
        }
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Capa')" :subheading="__('Atualizar sua foto de capa')">
        <form wire:submit.prevent="updateCover" class="my-6 w-full space-y-6">
            <div>
                <x-file-upload wire:model="cover" :label="__('Capa')" accept="image/png, image/jpeg" icon="photo" :iconVariant="$cover ? 'solid' : 'outline'" />

                @if (auth()->user() && auth()->user()->userCoverPhotos()->latest()->first())
                    @php
                        $coverPhoto = auth()->user()->userCoverPhotos()->latest()->first();
                        $coverPath = $coverPhoto->cropped_photo_path ?? $coverPhoto->photo_path;
                    @endphp
                    <div class="mt-4 relative">
                        <img src="{{ Storage::url($coverPath) }}" alt="Capa" class="w-full h-40 object-cover rounded">
                        <div class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                            {{ $coverPhoto->cropped_photo_path ? 'Recortada' : 'Original' }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">
                        <div class="flex items-center justify-center gap-2">
                            <x-flux::icon name="photo" class="w-5 h-5" />
                            <span>{{ __('Selecionar e Recortar Imagem') }}</span>
                        </div>
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="cover-updated">
                    {{ __('Capa atualizada.') }}
                </x-action-message>

                @if (session('error'))
                    <flux:text class="mt-2 font-medium !dark:text-red-400 !text-red-600">
                        {{ session('error') }}
                    </flux:text>
                @endif

                @if (session('success'))
                    <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                        {{ session('success') }}
                    </flux:text>
                @endif
            </div>
        </form>
    </x-settings.layout>

    <!-- Modal de Recorte de Imagem -->
    @if($showCropperModal)
    <div
        x-data="{
            cropper: null,
            isInitializing: false,
            initCropper() {
                // Evitar múltiplas inicializações simultâneas
                if (this.isInitializing || this.cropper) {
                    console.log('Cropper já está sendo inicializado ou já existe.');
                    return;
                }

                this.isInitializing = true;
                console.log('Inicializando cropper...');

                // Usar um único setTimeout para garantir que o DOM esteja pronto
                setTimeout(() => {
                    try {
                        const image = this.$refs.cropperImage;
                        console.log('Elemento de imagem:', image);

                        if (!image) {
                            console.error('Elemento de imagem não encontrado!');
                            this.isInitializing = false;
                            return;
                        }

                        // Verificar se o Cropper está disponível
                        if (typeof Cropper === 'undefined') {
                            console.error('Cropper não está definido!');
                            this.isInitializing = false;
                            return;
                        }

                        // Destruir instância anterior se existir
                        if (this.cropper) {
                            this.cropper.destroy();
                            this.cropper = null;
                        }

                        // Inicializar o Cropper
                        this.cropper = new Cropper(image, {
                            aspectRatio: 16/9, // Proporção retangular para capa
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
                            },
                            ready: () => {
                                console.log('Cropper está pronto!');
                                this.isInitializing = false;
                            }
                        });
                        console.log('Cropper inicializado com sucesso!');
                    } catch (error) {
                        console.error('Erro ao inicializar o Cropper:', error);
                        this.isInitializing = false;
                    }
                }, 500);
            },
            init() {
                // Observar mudanças na visibilidade do modal
                this.$watch('$wire.showCropperModal', (value) => {
                    console.log('Modal visibilidade alterada:', value);
                    if (value) {
                        // Modal aberto, inicializar o cropper após um pequeno atraso
                        // Apenas inicializar se ainda não estiver inicializado
                        if (!this.cropper && !this.isInitializing) {
                            this.initCropper();
                        }
                    } else {
                        // Modal fechado, destruir o cropper
                        this.destroyCropper();
                    }
                });

                // Inicializar o cropper se o modal já estiver aberto
                if (this.$wire.showCropperModal && !this.cropper && !this.isInitializing) {
                    this.initCropper();
                }
            },
            destroyCropper() {
                console.log('Destruindo cropper...');
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
                this.isInitializing = false;
            }
        }"
        x-init="init"
        @hidden.window="destroyCropper"
    >
        <flux:modal wire:model="showCropperModal" size="xl" wire:key="cropper-modal-{{ now() }}">
            <flux:modal.header>
                <flux:heading size="sm">Recortar Imagem de Capa</flux:heading>
            </flux:modal.header>

            <flux:modal.body>
                <div class="space-y-4">
                    @if($tempImagePath)
                        <div class="relative w-full h-[400px] bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden cropper-container-wrapper">
                            <img
                                x-ref="cropperImage"
                                src="{{ Storage::url($tempImagePath) }}"
                                class="max-w-full"
                                alt="Imagem para recorte"
                                style="max-height: 100%; display: block; max-width: 100%;"
                                @load="if (!cropper && !isInitializing) { initCropper(); }"
                            />
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
    </div>
    @endif
</section>
