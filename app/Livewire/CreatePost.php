<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class CreatePost extends Component
{
    use WithFileUploads;

    public $content = '';
    public $image = null;
    public $video = null;
    public $uploading = false;

    protected $rules = [
        'content' => 'required|min:3',
        'image' => 'nullable|image|max:10240',
        'video' => 'nullable|mimetypes:video/mp4|max:51200'
    ];

    public function store()
    {
        $this->uploading = true;
        try {
            $this->validate();

            $imagePath = null;
            if ($this->image) {
                $filename = time() . '_' . $this->image->getClientOriginalName();
                $imagePath = $this->image->storeAs('posts/images', $filename, 'public');
            }

            $post = Post::create([
                'content' => $this->content,
                'image' => $imagePath, // Corrigido para usar o campo correto
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
