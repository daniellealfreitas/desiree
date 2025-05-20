<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\Post;
use App\Models\UserPoint;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class GroupPosts extends Component
{
    use WithPagination, WithFileUploads;

    public $group;
    public $content = '';
    public $image;
    public $video;
    public $showPostForm = false;

    protected $rules = [
        'content' => 'nullable|string|max:1000',
        'image' => 'nullable|image|max:2048',
        'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240',
    ];

    protected $validationAttributes = [
        'content' => 'conteúdo',
        'image' => 'imagem',
        'video' => 'vídeo',
    ];

    public function mount(Group $group)
    {
        $this->group = $group;
        
        // Check if the user can view this group
        if ($this->group->privacy === 'secret' && !Auth::check()) {
            abort(404);
        }
        
        if ($this->group->privacy === 'secret' && !Auth::user()->isMemberOf($this->group)) {
            abort(403, 'Você não tem permissão para visualizar este grupo.');
        }
    }

    public function togglePostForm()
    {
        $this->showPostForm = !$this->showPostForm;
    }

    public function createPost()
    {
        // Check if the user is a member of the group
        if (!Auth::user()->isMemberOf($this->group)) {
            session()->flash('error', 'Você precisa ser membro do grupo para criar postagens.');
            return;
        }
        
        // Validate the post
        $this->validate();
        
        // Check if at least one field is filled
        if (empty($this->content) && !$this->image && !$this->video) {
            $this->addError('content', 'Você precisa adicionar conteúdo, uma imagem ou um vídeo.');
            return;
        }
        
        $data = [
            'content' => $this->content,
            'user_id' => Auth::id(),
            // 'group_id' => $this->group->id,
        ];
        
        // Handle image upload
        if ($this->image) {
            $data['image'] = $this->image->store('posts/images', 'public');
        }
        
        // Handle video upload
        if ($this->video) {
            $data['video'] = $this->video->store('posts/videos', 'public');
        }
        
        // Create the post
        $post = Post::create($data);
        
        // Reset the form
        $this->reset(['content', 'image', 'video']);
        $this->showPostForm = false;
        
        session()->flash('success', 'Postagem criada com sucesso!');
        
        // Add points for creating a post in a group
        UserPoint::addPoints(
            Auth::id(),
            'group_post',
            15,
            "Criou uma postagem no grupo: {$this->group->name}",
            $post->id,
            Post::class
        );
    }

    public function deletePost(Post $post)
    {
        // Check if the user can delete this post
        if (Auth::id() !== $post->user_id && !Auth::user()->canManageGroup($this->group)) {
            session()->flash('error', 'Você não tem permissão para excluir esta postagem.');
            return;
        }
        
        // Delete the post
        $post->delete();
        
        session()->flash('success', 'Postagem excluída com sucesso!');
    }

    public function render()
    {
        $isMember = Auth::check() ? Auth::user()->isMemberOf($this->group) : false;
        $canManage = Auth::check() ? Auth::user()->canManageGroup($this->group) : false;
        
        $posts = $this->group->posts()
                            ->with(['user', 'likes', 'comments'])
                            ->latest()
                            ->paginate(10);
        
        return view('livewire.groups.group-posts', [
            'posts' => $posts,
            'isMember' => $isMember,
            'canManage' => $canManage,
        ]);
    }
}
