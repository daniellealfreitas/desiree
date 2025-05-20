<?php 
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserPhoto;
use Illuminate\Support\Facades\Storage;

class Leaderboard extends Component
{
    public $topUsers;

    public function mount()
    {
        // Buscar os 10 usuários com mais pontos de ranking
        $this->topUsers = User::orderBy('ranking_points', 'desc')
            ->take(10)
            ->get();

        // Adicionar avatares aos usuários do ranking
        $this->topUsers->each(function ($user) {
            $user->avatar = $this->getAvatar($user->id);
        });
    }

    public function getAvatar($userId)
    {
        $path = UserPhoto::where('user_id', $userId)
            ->latest()
            ->value('photo_path');
        return $path ? Storage::url($path) : null;
    }

    public function render()
    {
        return view('livewire.leaderboard');
    }
}
