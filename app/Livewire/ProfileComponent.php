<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\UserPhoto;
use App\Models\UserCoverPhoto;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ProfileComponent extends Component
{
    public User $user;
    public array $followStatus = [];
    public $topUsers; // Add this property to store top users
    // Removido o gerenciamento de status, agora feito pelo componente UserStatusManager

    public function mount(string $username)
    {
        $this->user = User::with(['followers', 'posts'])
            ->where('username', $username)
            ->firstOrFail();

        $this->followStatus[$this->user->id] = $this->user->followers->contains(Auth::id());

        // Fetch top users ordered by ranking points
        $this->topUsers = User::orderBy('ranking_points', 'desc')->take(10)->get();

        // Add avatars to the top users
        $this->topUsers->each(function ($user) {
            $user->avatar = $this->getAvatar($user->id);
        });

        // Set the current user status
        $this->userStatus = $this->user->status;

        // Registrar a visita ao perfil
        $this->registerProfileVisit();
    }

    /**
     * Registra a visita ao perfil
     */
    protected function registerProfileVisit()
    {
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            return;
        }

        // Não registrar visitas ao próprio perfil
        if (Auth::id() === $this->user->id) {
            return;
        }

        // Registrar a visita
        Auth::user()->visitProfile($this->user);
    }

    public function avatar()
    {
        $path = UserPhoto::where('user_id', $this->user->id)->latest()->value('photo_path');
        return $path ? Storage::url($path) : null;
    }

    public function getAvatar($userId)
    {
        // Fetch the latest photo for a specific user
        $path = UserPhoto::where('user_id', $userId)
            ->latest()
            ->value('photo_path');
        return $path ? Storage::url($path) : asset('images/users/avatar.jpg');
    }

    public function cover()
    {
        $coverPhoto = UserCoverPhoto::where('user_id', $this->user->id)->latest()->first();
        if (!$coverPhoto) {
            return null;
        }

        // Usar a versão recortada se disponível, caso contrário usar a original
        $path = $coverPhoto->cropped_photo_path ?? $coverPhoto->photo_path;
        return $path ? Storage::url($path) : null;
    }

    public function toggleFollow($userId)
    {
        $user = User::find($userId);
        if ($this->followStatus[$userId]) {
            $user->followers()->detach(Auth::id());
            $this->followStatus[$userId] = false;
    } else {
            $user->followers()->attach(Auth::id());
            $this->followStatus[$userId] = true;
        }
    }

    public function imagesCount(): int
    {
        return Post::where('user_id', $this->user->id)
            ->whereNotNull('image')
            ->count();
    }

    public function videosCount(): int
    {
        return Post::where('user_id', $this->user->id)
            ->whereNotNull('video')
            ->count();
    }

    public function postsCount(): int
    {
        return Post::where('user_id', $this->user->id)->count();
    }

    public function followingCount(): int
    {
        return $this->user->following()->count();
    }

    public function followersCount(): int
    {
        return $this->user->followers()->count();
    }

    // Função simplificada para atualizar dados do perfil
    public function refreshStatus()
    {
        // Recarregar usuário para obter dados atualizados
        $this->user->refresh();
    }

    // Método para exibir imagens do usuário
    public function showUserImages()
    {
        $this->dispatch('show-user-images', userId: $this->user->id);
    }

    // Método para exibir vídeos do usuário
    public function showUserVideos()
    {
        $this->dispatch('show-user-videos', userId: $this->user->id);
    }

    // Método para exibir usuários que o perfil está seguindo
    public function showUserFollowing()
    {
        $this->dispatch('show-user-following', userId: $this->user->id);
    }

    // Método para exibir seguidores do perfil
    public function showUserFollowers()
    {
        $this->dispatch('show-user-followers', userId: $this->user->id);
    }

    // Método para exibir todas as postagens do usuário
    public function showUserPosts()
    {
        $this->dispatch('show-user-posts', userId: $this->user->id);
    }

    // Método para exibir o componente de envio de charm
    public function showSendCharm()
    {
        $this->dispatch('show-send-charm', userId: $this->user->id);
    }


    public function render()
    {
        return view('livewire.profile', [
            'topUsers' => $this->topUsers, // Pass top users to the view
        ]);
    }
}
