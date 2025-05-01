<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreatePost extends Component
{
    use WithFileUploads;

    public $content = '';
    public $image = null;
    public $video = null;
    public $uploading = false;

    protected $rules = [
        'content' => 'required|min:3',
        'image' => 'nullable|image|max:10240', // 10MB
        'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:102400' // 100MB
    ];

    protected $messages = [
        'video.max' => 'O vídeo não pode ser maior que 100MB.',
        'video.mimetypes' => 'O vídeo deve estar no formato MP4 ou MOV.',
    ];

    public function store()
    {
        $this->uploading = true;
        try {
            $this->validate();

            $imagePath = null;
            if ($this->image) {
                try {
                    $filename = time() . '_' . $this->image->getClientOriginalName();
                    $imagePath = $this->image->storeAs('posts/images', $filename, 'public');
                    if (!$imagePath) {
                        throw new \Exception('Falha ao salvar a imagem');
                    }
                } catch (\Exception $e) {
                    Log::error('Error uploading image: ' . $e->getMessage());
                    throw new \Exception('Erro ao fazer upload da imagem: ' . $e->getMessage());
                }
            }

            $videoPath = null;
            if ($this->video) {
                try {
                    $videoFilename = time() . '_' . $this->video->getClientOriginalName();
                    $videoPath = $this->video->storeAs('posts/videos', $videoFilename, 'public');
                    if (!$videoPath) {
                        throw new \Exception('Falha ao salvar o vídeo');
                    }
                } catch (\Exception $e) {
                    Log::error('Error uploading video: ' . $e->getMessage());
                    if ($imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                    throw new \Exception('Erro ao fazer upload do vídeo: ' . $e->getMessage());
                }
            }

            $post = Post::create([
                'content' => $this->content,
                'image' => $imagePath,
                'video' => $videoPath,
                'user_id' => auth()->id(),
            ]);

            if ($post) {
                Log::info('Post created successfully', ['post' => $post->toArray()]);
                $this->reset(['content', 'image', 'video']);
                session()->flash('message', 'Post criado com sucesso!');
                return redirect()->route('dashboard');
            }
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            session()->flash('error', 'Erro ao criar o post: ' . $e->getMessage());
        } finally {
            $this->uploading = false;
        }
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
