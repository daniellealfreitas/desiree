<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Support\Facades\Auth;

class SwipeMatch extends Component
{
    public $users = [];
    public $index = 0;

    public function mount()
    {
        $this->users = $this->loadCandidates();
    }

    public function loadCandidates()
    {
        $current = Auth::user();

        // Verifica se o usuário tem as coordenadas
        if (!$current->latitude || !$current->longitude) {
            session()->flash('error', 'Sua localização não está disponível.');
            return [];
        }

        // Carrega usuários que não sejam o atual, e que não tenham sido "passados"
        return User::where('id', '!=', $current->id)
            ->whereNotIn('id', function ($q) use ($current) {
                $q->select('target_user_id')
                    ->from('user_matches')
                    ->where('user_id', $current->id);
            })
            ->with('photos') // Carrega as fotos dos usuários
            ->get()
            ->filter(function ($user) use ($current) {
                // Calcula a distância de cada usuário
                $distance = $this->calculateDistance(
                    $current->latitude, $current->longitude,
                    $user->latitude, $user->longitude
                );
                
                // Se a distância for até 50km, inclui no resultado
                return $distance ;
            })
            ->values();
    }

    public function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Raio da Terra em quilômetros

        // Converter as coordenadas de graus para radianos
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        // Diferenças
        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;

        // Fórmula de Haversine
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c; // Distância em quilômetros

        return $distance;
    }

    public function like()
    {
        $this->storeMatch(true);
    }

    public function pass()
    {
        $this->storeMatch(false);
    }

    public function storeMatch($liked)
    {
        $me = Auth::user();
        $target = $this->users[$this->index] ?? null;

        if (!$target) return;

        // Cria o match (curtida ou pass)
        UserMatch::create([
            'user_id' => $me->id,
            'target_user_id' => $target->id,
            'liked' => $liked
        ]);

        // Verifica se houve match recíproco
        if ($liked) {
            $reciprocal = UserMatch::where('user_id', $target->id)
                ->where('target_user_id', $me->id)
                ->where('liked', true)
                ->first();

            if ($reciprocal) {
                session()->flash('match', "🎉 Você deu match com {$target->name}!");
            }
        }

        $this->index++;
    }

    public function render()
    {
        // Verifica se tem usuários disponíveis para mostrar
        return view('livewire.swipe-match', [
            'currentUser' => $this->users[$this->index] ?? null
        ]);
    }
}
