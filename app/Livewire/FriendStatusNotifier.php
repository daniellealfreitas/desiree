<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class FriendStatusNotifier extends Component
{
    public $lastCheckedAt;
    public $newOnlineFriends = [];
    
    public function mount()
    {
        $this->lastCheckedAt = now();
    }
    
    public function checkFriendsStatus()
    {
        if (!Auth::check()) {
            return;
        }
        
        $user = Auth::user();
        
        // Obter amigos que ficaram online desde a última verificação
        $onlineFriends = $user->following()
            ->where('status', 'online')
            ->where('last_seen', '>', $this->lastCheckedAt)
            ->get();
        
        // Atualizar o timestamp da última verificação
        $this->lastCheckedAt = now();
        
        // Se não houver amigos online, não fazer nada
        if ($onlineFriends->isEmpty()) {
            return;
        }
        
        // Armazenar os amigos que ficaram online
        $this->newOnlineFriends = $onlineFriends->map(function ($friend) {
            return [
                'id' => $friend->id,
                'name' => $friend->name,
                'username' => $friend->username,
                'avatar' => $friend->userPhotos->first() ? $friend->userPhotos->first()->photo_path : null,
            ];
        })->toArray();
        
        // Criar notificações para cada amigo que ficou online
        foreach ($onlineFriends as $friend) {
            // Verificar se o usuário não está em modo "Não Perturbe"
            if ($user->status !== 'dnd') {
                // Criar notificação apenas se o usuário não estiver em modo "Não Perturbe"
                Notification::create([
                    'user_id' => $user->id,
                    'sender_id' => $friend->id,
                    'type' => 'friend_online',
                    'message' => $friend->name . ' acabou de ficar online.',
                    'read' => false,
                ]);
            }
        }
        
        // Emitir evento para atualizar o contador de notificações
        $this->dispatch('newNotifications');
    }
    
    public function render()
    {
        return view('livewire.friend-status-notifier');
    }
}
