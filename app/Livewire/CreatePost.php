<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreatePost extends Component
{
    use WithFileUploads;

    public $content = '';
    public $image = null;
    public $video = null;
    public $uploading = false;

    protected function rules()
    {
        return [
            'content' => $this->image || $this->video ? 'nullable' : 'required|min:3',
            'image' => 'nullable|image|max:10240', // 10MB
            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:102400' // 100MB
        ];
    }

    protected $messages = [
        'video.max' => 'O vídeo não pode ser maior que 100MB.',
        'video.mimetypes' => 'O vídeo deve estar no formato MP4 ou MOV.',
    ];

    public function store()
    {
        $this->uploading = true;
        try {
            $this->validate();

            // Ensure at least one of content, image, or video is present
            if (empty($this->content) && !$this->image && !$this->video) {
                session()->flash('error', 'É necessário fornecer pelo menos um conteúdo, imagem ou vídeo.');
                $this->uploading = false;
                return;
            }

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
                'group_id' => null, // Definindo explicitamente como null para posts fora de grupos
            ]);

            if ($post) {
                Log::info('Post created successfully', ['post' => $post->toArray()]);

                $user = auth()->user();
                $pointsToAdd = 10; // Base points

                // Bônus por conteúdo multimídia
                if ($imagePath) $pointsToAdd += 5;
                if ($videoPath) $pointsToAdd += 10;

                // Bônus por tamanho do conteúdo
                if (strlen($this->content) > 100) $pointsToAdd += 5;

                // Adiciona pontos ao usuário
                \App\Models\UserPoint::addPoints(
                    $user->id,
                    'post',
                    $pointsToAdd,
                    "Criou uma nova postagem" .
                    ($imagePath ? " com imagem" : "") .
                    ($videoPath ? " com vídeo" : ""),
                    $post->id,
                    \App\Models\Post::class
                );

                // Dispara evento para animação de recompensa
                $this->dispatch('reward-earned', points: $pointsToAdd);

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
