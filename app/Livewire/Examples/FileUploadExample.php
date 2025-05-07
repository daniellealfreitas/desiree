<?php

namespace App\Livewire\Examples;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class FileUploadExample extends Component
{
    use WithFileUploads;

    public $image = null;
    public $documents = [];
    public $video = null;

    protected function rules()
    {
        return [
            'image' => 'nullable|image|max:2048', // 2MB
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx|max:10240', // 10MB
            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:51200', // 50MB
        ];
    }

    public function save()
    {
        $this->validate();

        // Example of saving files
        if ($this->image) {
            $imagePath = $this->image->store('uploads/images', 'public');
            // Save $imagePath to database
        }

        if (count($this->documents) > 0) {
            foreach ($this->documents as $document) {
                $documentPath = $document->store('uploads/documents', 'public');
                // Save each $documentPath to database
            }
        }

        if ($this->video) {
            $videoPath = $this->video->store('uploads/videos', 'public');
            // Save $videoPath to database
        }

        session()->flash('message', 'Arquivos enviados com sucesso!');
        
        // Reset form
        $this->reset(['image', 'documents', 'video']);
    }

    public function render()
    {
        return view('examples.file-upload-example');
    }
}
