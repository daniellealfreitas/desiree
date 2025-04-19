<?php 
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class Leaderboard extends Component
{
    public $topUsers;

    public function mount()
    {
        // Fetch top users ordered by ranking points
        $this->topUsers = User::orderBy('ranking_points', 'desc')->take(10)->get();
    }

    public function render()
    {
        return view('livewire.leaderboard', [
            'topUsers' => $this->topUsers, // Pass the variable to the view
        ]);
    }
}