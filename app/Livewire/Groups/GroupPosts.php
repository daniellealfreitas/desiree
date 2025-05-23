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
    public $newComment = [];
    public $showComments = [];

    protected $rules = [
        'content' => 'nullable|string|max:1000',
        'image' => 'nullable|image|max:2048',
        'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240',
        'newComment.*' => 'required|string|min:1',
    ];

    protected $validationAttributes = [
        'content' => 'conteúdo',
        'image' => 'imagem',
        'video' => 'vídeo',
        'newComment.*' => 'comentário',
    ];

    protected function getListeners()
    {
        return [
            'toggleLike',
            'focusComment',
        ];
    }

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
            'group_id' => $this->group->id,
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

    public function toggleLike($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $postId = $id;
        $post = Post::findOrFail($postId);
        $user = Auth::user();

        if (!$user) return;

        $wasLiked = $post->isLikedBy($user);

        if ($wasLiked) {
            // Remove curtida
            $post->likedByUsers()->detach($user->id);

            // Remove notificação
            \App\Models\Notification::where([
                'sender_id' => $user->id,
                'post_id' => $post->id,
                'type' => 'like'
            ])->delete();

            // Remove pontos (apenas se o post não for do próprio usuário)
            if ($post->user_id !== $user->id) {
                UserPoint::removePoints(
                    $post->user_id,
                    'like',
                    5,
                    "Perdeu curtida de {$user->name}",
                    $post->id,
                    Post::class
                );
            }
        } else {
            // Adiciona curtida
            $post->likedByUsers()->attach($user->id);

            // Adiciona pontos ao usuário que curtiu (recompensa por engajamento)
            UserPoint::addPoints(
                $user->id,
                'like',
                2,
                "Curtiu postagem de " . ($post->user_id === $user->id ? "sua autoria" : $post->user->name),
                $post->id,
                Post::class
            );

            // Adiciona pontos ao autor do post (se não for o mesmo usuário)
            if ($post->user_id !== $user->id) {
                UserPoint::addPoints(
                    $post->user_id,
                    'like_received',
                    5,
                    "Recebeu curtida de {$user->name}",
                    $post->id,
                    Post::class
                );
            }

            // Dispara animação de recompensa
            $this->dispatch('reward-earned', points: 2);

            // Cria notificação se não for post próprio
            if ($post->user_id !== $user->id) {
                \App\Models\Notification::create([
                    'user_id' => $post->user_id,
                    'sender_id' => $user->id,
                    'type' => 'like',
                    'post_id' => $post->id
                ]);
            }
        }
    }

    public function focusComment($id)
    {
        $postId = $id;
        $this->showComments[$postId] = true;
    }

    public function addComment($postId)
    {
        $this->validate([
            "newComment.$postId" => 'required|min:1'
        ]);

        $post = Post::findOrFail($postId);
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $comment = \App\Models\Comment::create([
            'user_id' => $user->id,
            'post_id' => $postId,
            'body' => $this->newComment[$postId]
        ]);

        // Adiciona pontos ao usuário que comentou
        UserPoint::addPoints(
            $user->id,
            'comment',
            5,
            "Comentou na postagem de " . ($post->user_id === $user->id ? "sua autoria" : $post->user->name),
            $comment->id,
            \App\Models\Comment::class
        );

        // Adiciona pontos ao autor do post (se não for o mesmo usuário)
        if ($post->user_id !== $user->id) {
            UserPoint::addPoints(
                $post->user_id,
                'comment_received',
                3,
                "Recebeu comentário de {$user->name}",
                $comment->id,
                \App\Models\Comment::class
            );

            // Cria notificação
            \App\Models\Notification::create([
                'user_id' => $post->user_id,
                'sender_id' => $user->id,
                'type' => 'comment',
                'post_id' => $post->id,
                'comment_id' => $comment->id
            ]);
        }

        // Limpa o campo de comentário
        $this->newComment[$postId] = '';
    }

    public function getAvatar($userId)
    {
        $path = \App\Models\UserPhoto::where('user_id', $userId)
            ->latest()
            ->value('photo_path');
        return $path ? asset('storage/' . $path) : asset('images/users/avatar.jpg');
    }

    public function render()
    {
        $isMember = Auth::check() ? Auth::user()->isMemberOf($this->group) : false;
        $canManage = Auth::check() ? Auth::user()->canManageGroup($this->group) : false;

        $posts = $this->group->posts()
                            ->with(['user.userPhotos', 'likedByUsers', 'comments.user.userPhotos'])
                            ->latest()
                            ->paginate(10);

        return view('livewire.groups.group-posts', [
            'posts' => $posts,
            'isMember' => $isMember,
            'canManage' => $canManage,
        ]);
    }
}
