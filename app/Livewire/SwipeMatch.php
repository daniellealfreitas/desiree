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
    public $maxDistance = 50; // Default max distance in km
    public $showLocationError = false;
    public $locationErrorMessage = '';
    public $likedUsers = []; // Lista de usuários curtidos

    public function mount()
    {
        $this->users = $this->loadCandidates();
        $this->loadLikedUsers();
    }

    /**
     * Este método é chamado após cada atualização do componente
     * para garantir que temos dados atualizados
     */
    public function updated()
    {
        // Se não houver usuários, recarrega os candidatos
        if (count($this->users) === 0) {
            $this->users = $this->loadCandidates();
            $this->index = 0;
        }
    }

    /**
     * Este método é chamado após cada renderização do componente
     */
    public function dehydrate()
    {
        // Garante que o componente seja recarregado completamente após cada ação
        $this->dispatch('refresh');
    }

    /**
     * Carrega a lista de usuários que o usuário atual curtiu
     */
    protected function loadLikedUsers()
    {
        $likedMatches = UserMatch::where('user_id', Auth::id())
            ->where('liked', true)
            ->with('targetUser')
            ->get();

        $this->likedUsers = $likedMatches->map(function($match) {
            $user = $match->targetUser;
            return [
                'user' => $user,
                'hasMatched' => $match->is_matched,
                'matchedAt' => $match->matched_at
            ];
        })->toArray();
    }

    public function updatedMaxDistance()
    {
        // Reload candidates when max distance changes
        $this->users = $this->loadCandidates();
        $this->index = 0; // Reset index to start from the beginning
    }

    /**
     * Método para recarregar os candidatos quando a página é atualizada
     * Este método é chamado pelo hook de Livewire quando a página é carregada
     */
    public function hydrate()
    {
        // Recarrega os candidatos quando a página é atualizada
        // Isso garante que tenhamos os dados mais recentes após uma atualização de localização
        $this->users = $this->loadCandidates();
        $this->index = 0;
    }

    /**
     * Carrega os candidatos para o radar
     *
     * @return \Illuminate\Support\Collection
     */
    public function loadCandidates()
    {
        $current = Auth::user();

        // Verifica se o usuário tem as coordenadas
        if (!$current->latitude || !$current->longitude) {
            $this->showLocationError = true;
            $this->locationErrorMessage = 'Sua localização não está disponível. Permita o acesso à sua localização no navegador ou atualize manualmente nas configurações.';
            return collect([]);
        }

        // Carrega usuários que não sejam o atual, e que não tenham sido "passados" ou "curtidos"
        $users = User::where('id', '!=', $current->id)
            ->whereNotIn('id', function ($q) use ($current) {
                $q->select('target_user_id')
                    ->from('user_matches')
                    ->where('user_id', $current->id); // Exclui todos os usuários que já receberam interação (pass ou like)
            })
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['photos', 'city']) // Carrega as fotos e cidade dos usuários
            ->get();

        // Filtra e adiciona a distância a cada usuário
        $filteredUsers = $users->map(function ($user) use ($current) {
                // Verifica se os valores de latitude e longitude são válidos
                if (
                    empty($current->latitude) || empty($current->longitude) ||
                    empty($user->latitude) || empty($user->longitude) ||
                    !is_numeric($current->latitude) || !is_numeric($current->longitude) ||
                    !is_numeric($user->latitude) || !is_numeric($user->longitude)
                ) {
                    // Atribui uma distância grande para usuários sem coordenadas válidas
                    $user->distance = 999999;
                    return $user;
                }

                // Calcula a distância de cada usuário
                $distance = $this->calculateDistance(
                    $current->latitude, $current->longitude,
                    $user->latitude, $user->longitude
                );

                // Adiciona a distância ao objeto do usuário
                $user->distance = $distance;

                return $user;
            })
            ->filter(function ($user) {
                // Filtra usuários pela distância máxima definida e com distância válida
                return $user->distance <= $this->maxDistance && $user->distance < 999999;
            })
            ->sortBy('distance') // Ordena por distância (mais próximos primeiro)
            ->values();

        if ($filteredUsers->isEmpty()) {
            if ($users->isEmpty()) {
                $this->showLocationError = true;
                $this->locationErrorMessage = 'Não encontramos outros usuários com localização definida no sistema.';
            } else {
                $this->showLocationError = true;
                $this->locationErrorMessage = 'Não encontramos usuários próximos dentro de ' . $this->maxDistance . 'km. Tente aumentar a distância.';
            }
        } else {
            $this->showLocationError = false;
        }

        return $filteredUsers;
    }

    public function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        // Garantir que todos os valores são numéricos
        $lat1 = (float) $lat1;
        $lng1 = (float) $lng1;
        $lat2 = (float) $lat2;
        $lng2 = (float) $lng2;

        // Verificar se algum valor é inválido
        if (!is_numeric($lat1) || !is_numeric($lng1) || !is_numeric($lat2) || !is_numeric($lng2)) {
            return 999999; // Retorna um valor grande para indicar distância inválida
        }

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

    public function like($userId = null)
    {
        // Guarda o usuário atual antes de interagir
        $currentUser = null;
        if ($userId === null && isset($this->users[$this->index])) {
            $currentUser = $this->users[$this->index];
        }

        $this->storeMatch(true, $userId);

        // Recarrega a lista de usuários curtidos
        $this->loadLikedUsers();

        // Remove o usuário da lista
        if ($userId !== null) {
            // Remove o usuário específico da lista
            $this->users = $this->users->reject(function ($user) use ($userId) {
                return $user->id === $userId;
            })->values();
        } else if ($currentUser) {
            // Remove o usuário atual da lista
            $this->users = $this->users->reject(function ($user) use ($currentUser) {
                return $user->id === $currentUser->id;
            })->values();
        }

        // Garante que o índice não ultrapasse o tamanho da lista
        if ($this->index >= count($this->users)) {
            $this->index = 0;
        }

        // Se não houver mais usuários, recarrega os candidatos
        if (count($this->users) === 0) {
            $this->users = $this->loadCandidates();
            $this->index = 0;
        }

        // Força a atualização da interface
        $this->dispatch('userLiked');
    }

    public function pass($userId = null)
    {
        // Guarda o usuário atual antes de interagir
        $currentUser = null;
        if ($userId === null && isset($this->users[$this->index])) {
            $currentUser = $this->users[$this->index];
        }

        $this->storeMatch(false, $userId);

        // Remove o usuário da lista
        if ($userId !== null) {
            // Remove o usuário específico da lista
            $this->users = $this->users->reject(function ($user) use ($userId) {
                return $user->id === $userId;
            })->values();
        } else if ($currentUser) {
            // Remove o usuário atual da lista
            $this->users = $this->users->reject(function ($user) use ($currentUser) {
                return $user->id === $currentUser->id;
            })->values();
        }

        // Garante que o índice não ultrapasse o tamanho da lista
        if ($this->index >= count($this->users)) {
            $this->index = 0;
        }

        // Se não houver mais usuários, recarrega os candidatos
        if (count($this->users) === 0) {
            $this->users = $this->loadCandidates();
            $this->index = 0;
        }

        // Força a atualização da interface
        $this->dispatch('userPassed');
    }

    public function storeMatch($liked, $userId = null)
    {
        $me = Auth::user();
        $targetId = null;

        // Se um ID específico foi passado (da lista de usuários próximos)
        if ($userId) {
            $target = $this->users->firstWhere('id', $userId);
            $targetId = $userId;
        } else {
            // Caso contrário, usa o usuário atual do carrossel
            $target = $this->users[$this->index] ?? null;

            if ($target) {
                $targetId = $target->id;
            }
        }

        // Não removemos o usuário da lista aqui, isso será feito nos métodos like() e pass()
        // após a atualização do banco de dados

        if (!$target) return;

        // Verifica se já existe um match anterior
        $existingMatch = UserMatch::where('user_id', $me->id)
            ->where('target_user_id', $target->id)
            ->first();

        if ($existingMatch) {
            // Atualiza o match existente
            $existingMatch->update([
                'liked' => $liked
            ]);

            $userMatch = $existingMatch;
        } else {
            // Cria um novo match
            $userMatch = UserMatch::create([
                'user_id' => $me->id,
                'target_user_id' => $target->id,
                'liked' => $liked
            ]);
        }

        // Verifica se houve match recíproco (apenas se o usuário deu like)
        if ($liked) {
            $reciprocal = UserMatch::where('user_id', $target->id)
                ->where('target_user_id', $me->id)
                ->where('liked', true)
                ->first();

            if ($reciprocal) {
                // Atualiza ambos os registros para indicar que houve match
                $userMatch->update([
                    'is_matched' => true,
                    'matched_at' => now()
                ]);

                $reciprocal->update([
                    'is_matched' => true,
                    'matched_at' => now()
                ]);

                // Notifica o usuário sobre o match
                $this->showMatchNotification($target);
            }
        } else {
            // Se o usuário deu "pass", verifica se havia um match anterior e remove
            $reciprocal = UserMatch::where('user_id', $target->id)
                ->where('target_user_id', $me->id)
                ->where('is_matched', true)
                ->first();

            if ($reciprocal) {
                // Remove o status de match
                $userMatch->update([
                    'is_matched' => false,
                    'matched_at' => null
                ]);

                $reciprocal->update([
                    'is_matched' => false,
                    'matched_at' => null
                ]);
            }
        }
    }

    /**
     * Exibe a notificação de match
     */
    protected function showMatchNotification($matchedUser)
    {
        // Exibe a notificação de match
        session()->flash('match', [
            'user' => $matchedUser,
            'message' => "🎉 Você deu match com {$matchedUser->name}!"
        ]);

        // Aqui você pode adicionar lógica para enviar notificações push, emails, etc.
    }

    /**
     * Verifica se o usuário atual já deu like em um usuário específico
     */
    public function hasLiked($userId)
    {
        return UserMatch::where('user_id', Auth::id())
            ->where('target_user_id', $userId)
            ->where('liked', true)
            ->exists();
    }

    /**
     * Verifica se o usuário atual já deu pass em um usuário específico
     */
    public function hasPassed($userId)
    {
        return UserMatch::where('user_id', Auth::id())
            ->where('target_user_id', $userId)
            ->where('liked', false)
            ->exists();
    }

    /**
     * Verifica se o usuário atual já deu match com um usuário específico
     */
    public function hasMatched($userId)
    {
        return UserMatch::where('user_id', Auth::id())
            ->where('target_user_id', $userId)
            ->where('is_matched', true)
            ->exists();
    }

    /**
     * Avança para o próximo usuário manualmente
     */
    public function nextUser()
    {
        if (count($this->users) > 0) {
            $this->index = ($this->index + 1) % count($this->users);
        } else {
            // Se não houver usuários, tenta recarregar
            $this->users = $this->loadCandidates();
            $this->index = 0;
        }

        // Força a atualização da interface
        $this->dispatch('userPassed');
    }

    /**
     * Recarrega todos os candidatos manualmente
     */
    public function reloadCandidates()
    {
        $this->users = $this->loadCandidates();
        $this->index = 0;
        $this->loadLikedUsers();
    }

    public function render()
    {
        // Se não houver usuários, tenta recarregar os candidatos
        if (count($this->users) === 0) {
            $this->users = $this->loadCandidates();
        }

        // Garante que o índice não ultrapasse o tamanho da lista
        if ($this->index >= count($this->users)) {
            $this->index = 0;
        }

        // Verifica novamente se temos usuários após a recarga
        if (count($this->users) === 0) {
            // Se ainda não tiver usuários, cria um array vazio
            $currentUser = null;
        } else {
            $currentUser = $this->users[$this->index];
        }

        // Prepara dados para a view
        $userData = [];

        if ($currentUser) {
            $userData = [
                'user' => $currentUser,
                'hasLiked' => $this->hasLiked($currentUser->id),
                'hasPassed' => $this->hasPassed($currentUser->id),
                'hasMatched' => $this->hasMatched($currentUser->id)
            ];
        }

        // Prepara dados para a lista de usuários próximos
        // Excluímos o usuário atual da lista de usuários próximos para evitar duplicação
        $nearbyUsers = collect($this->users)
            ->filter(function ($user) use ($currentUser) {
                return $currentUser ? $user->id !== $currentUser->id : true;
            })
            ->map(function ($user) {
                return [
                    'user' => $user,
                    'hasLiked' => $this->hasLiked($user->id),
                    'hasPassed' => $this->hasPassed($user->id),
                    'hasMatched' => $this->hasMatched($user->id)
                ];
            });

        // Verifica se tem usuários disponíveis para mostrar
        return view('livewire.swipe-match', [
            'currentUser' => $userData,
            'nearbyUsers' => $nearbyUsers,
            'showLocationError' => $this->showLocationError,
            'locationErrorMessage' => $this->locationErrorMessage,
            'maxDistance' => $this->maxDistance,
            'likedUsers' => $this->likedUsers
        ]);
    }
}
