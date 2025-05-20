<?php

namespace App\Livewire\Debug;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadDebug extends Component
{
    use WithFileUploads;

    public $file = null;
    public $uploadStatus = null;
    public $errorMessage = null;

    public function updatedFile()
    {
        try {
            $this->uploadStatus = 'Arquivo recebido: ' . $this->file->getClientOriginalName();
            Log::info('File upload debug - File received', [
                'filename' => $this->file->getClientOriginalName(),
                'size' => $this->file->getSize(),
                'mime' => $this->file->getMimeType()
            ]);
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro ao processar arquivo: ' . $e->getMessage();
            Log::error('File upload debug - Error processing file', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function save()
    {
        try {
            $this->validate([
                'file' => 'required|file|max:10240', // 10MB
            ]);

            $path = $this->file->store('debug-uploads', 'public');
            
            $this->uploadStatus = 'Arquivo salvo com sucesso: ' . Storage::url($path);
            $this->errorMessage = null;
            
            Log::info('File upload debug - File saved', [
                'path' => $path,
                'url' => Storage::url($path)
            ]);
            
            $this->reset('file');
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro ao salvar arquivo: ' . $e->getMessage();
            Log::error('File upload debug - Error saving file', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.debug.file-upload-debug');
    }
}
