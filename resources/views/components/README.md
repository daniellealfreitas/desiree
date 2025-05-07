# Componente de Upload de Arquivo

Este componente foi criado para substituir o `flux::file` que não está disponível no projeto. Ele usa Tailwind CSS e Livewire 3 para criar um componente de upload de arquivo estilizado e funcional.

> **Importante**: Este componente foi projetado para lidar tanto com objetos de arquivo quanto com strings de caminho, evitando erros como "Call to a member function getClientOriginalName() on string".

## Como usar

### Upload de arquivo único

```blade
<x-file-upload
    wire:model="image"
    label="Imagem"
    accept="image/*"
    icon="photo"
    :iconVariant="$image ? 'solid' : 'outline'"
/>
```

### Upload de múltiplos arquivos

```blade
<x-file-upload
    wire:model="documents"
    label="Documentos"
    accept=".pdf,.doc,.docx"
    multiple
    icon="document-text"
/>
```

### Upload de vídeo

```blade
<x-file-upload
    wire:model="video"
    label="Vídeo"
    accept="video/*"
    icon="video-camera"
    :iconVariant="$video ? 'solid' : 'outline'"
/>
```

## Propriedades

| Propriedade    | Tipo      | Padrão                | Descrição                                                |
|----------------|-----------|----------------------|----------------------------------------------------------|
| label          | string    | null                 | Rótulo do campo de upload                                |
| accept         | string    | null                 | Tipos de arquivo aceitos (ex: "image/*", ".pdf,.doc")    |
| multiple       | boolean   | false                | Permite upload de múltiplos arquivos                     |
| error          | string    | null                 | Mensagem de erro a ser exibida                           |
| id             | string    | "file-" + uniqid()   | ID do elemento input                                     |
| showFilename   | boolean   | true                 | Exibe o nome do arquivo após o upload                    |
| icon           | string    | "document-text"      | Ícone a ser exibido (usando flux::icon)                  |
| iconVariant    | string    | "outline"            | Variante do ícone (outline, solid)                       |

## Exemplo no Livewire Component

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ExampleUpload extends Component
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

        // Salvar arquivos
        if ($this->image) {
            $imagePath = $this->image->store('uploads/images', 'public');
            // Salvar $imagePath no banco de dados
        }

        // Resetar formulário
        $this->reset(['image', 'documents', 'video']);
    }

    public function render()
    {
        return view('livewire.example-upload');
    }
}
```
