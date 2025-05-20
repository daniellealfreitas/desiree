<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Canal privado para conversas
Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    // Verificar se o usuário é participante da conversa
    $conversation = Conversation::findOrFail($conversationId);
    return $conversation->participants()->where('user_id', $user->id)->exists();
});

// Canal de presença para status online
Broadcast::channel('presence.chat.{conversationId}', function ($user, $conversationId) {
    // Verificar se o usuário é participante da conversa
    $conversation = Conversation::findOrFail($conversationId);
    if ($conversation->participants()->where('user_id', $user->id)->exists()) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});

// Canal público para status de usuário
Broadcast::channel('user-status', function ($user) {
    return true;
});
