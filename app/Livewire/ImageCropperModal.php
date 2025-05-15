<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Laravel\Facades\Image;

class ImageCropperModal extends Component
{
    use WithFileUploads;

    public $image;
    public $showModal = false;
    public $tempImagePath;
    public $cropData = [
        'x' => 0,
        'y' => 0,
        'width' => 0,
        'height' => 0
    ];
    public $aspectRatio = 1; // Padrão 1:1 (quadrado)
    public $destinationFolder = 'images';
    public $modalTitle = 'Recortar Imagem';
    public $onImageSaved = 'image-saved';

    // Observador para abrir o modal automaticamente quando uma imagem for selecionada
    public function updatedImage()
    {
        if ($this->image) {
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
                'image' => ['required', 'image', 'mimes:jpg,png', 'max:5120'], // Permite JPG/PNG até 5MB
            ]);

            if ($this->image) {
                // Armazena o arquivo temporariamente e obtém o caminho
                $this->tempImagePath = $this->image->store('temp', 'public');

                if (!$this->tempImagePath) {
                    throw new \Exception('Falha ao armazenar a imagem temporária.');
                }

                // Mostra o modal com o cropper
                $this->showModal = true;
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
            $imagePath = $this->destinationFolder . '/' . basename($this->tempImagePath);
            Storage::disk('public')->copy($this->tempImagePath, $imagePath);

            // Criar a versão recortada
            $croppedPath = $this->destinationFolder . '/cropped_' . basename($this->tempImagePath);

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

            // Limpar o arquivo temporário
            Storage::disk('public')->delete($this->tempImagePath);

            // Resetar o estado do componente
            $this->reset(['image', 'showModal', 'tempImagePath', 'cropData']);

            // Disparar evento com os caminhos das imagens
            $this->dispatch($this->onImageSaved, [
                'original' => $imagePath,
                'cropped' => $croppedPath
            ]);

            // Adicionar mensagem de sucesso
            session()->flash('success', 'Imagem salva com sucesso!');
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
        $this->reset(['image', 'showModal', 'tempImagePath', 'cropData']);
    }

    public function render()
    {
        return view('livewire.image-cropper-modal');
    }
}
