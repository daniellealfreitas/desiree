<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageNotifier extends Component
{
    public $lastCheck;

    public function mount()
    {
        // Inicializar o timestamp da última verificação
        $this->lastCheck = now()->subMinute();
        Log::info('MessageNotifier: Componente montado');
    }

    /**
     * Verifica se há novas mensagens não lidas e mostra notificações
     */
    public function checkNewMessages()
    {
        if (!Auth::check()) {
            return;
        }

        // Buscar mensagens não lidas recentes (desde a última verificação)
        // Garantir que sejam apenas mensagens recebidas (receiver_id = usuário atual)
        $newMessages = Message::where('receiver_id', Auth::id())
            ->where('sender_id', '!=', Auth::id()) // Garantir que não são mensagens enviadas pelo próprio usuário
            ->whereNull('read_at')
            ->where('created_at', '>=', $this->lastCheck)
            ->with(['sender', 'sender.userPhotos'])
            ->get();

        // Atualizar o timestamp da última verificação
        $this->lastCheck = now();

        // Para cada mensagem nova, mostrar uma notificação toast
        foreach ($newMessages as $message) {
            $senderName = $message->sender->name ?? 'Alguém';
            $messagePreview = \Illuminate\Support\Str::limit($message->body, 50);

            // Disparar notificação toast usando o componente ToastNotification
            Log::info('MessageNotifier: Enviando notificação de mensagem para ToastNotification', [
                'sender' => $senderName,
                'preview' => $messagePreview,
                'receiver_id' => Auth::id(),
                'message_id' => $message->id
            ]);

            // Obter avatar do remetente ou usar avatar padrão
            $avatar = null;
            if ($message->sender->userPhotos->first()) {
                $avatar = asset($message->sender->userPhotos->first()->photo_path);
            } else {
                // Usar avatar padrão do Flux UI
                $avatar = asset('vendor/fluxui/img/avatar.jpg');
            }

            // Usar apenas um método para enviar a notificação (evitar duplicações)
            // Usando dispatch para o componente específico
            $this->dispatch('toast',
                message: "{$senderName}: {$messagePreview}",
                type: 'message',
                timeout: 5000,
                avatar: $avatar,
                senderId: $message->sender_id
            )->to('toast-notification');
        }
    }

    public function render()
    {
        // Verificar novas mensagens a cada renderização
        $this->checkNewMessages();

        return view('livewire.message-notifier');
    }
}
