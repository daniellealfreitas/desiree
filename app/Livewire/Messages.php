<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;
use App\Models\Notification;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Messages extends Component
{
    use WithPagination;

    public $selectedConversation = null;
    public $messageBody = '';
    public $searchTerm = '';
    public $users = [];
    public $conversations = [];
    public $unreadCount = 0;

    // Listeners para eventos no Livewire 3
    protected function getListeners()
    {
        return [
            'message-received' => 'handleMessageReceived',
            'user-status-changed' => 'handleUserStatusChanged'
        ];
    }

    public $currentConversationId = null;

    public function mount()
    {
        $this->loadConversations();
        $this->loadUsers();

        // Atualizar status do usuário para online
        $user = Auth::user();
        if ($user->status !== 'online') {
            $user->update([
                'status' => 'online',
                'last_seen' => now()
            ]);

            // Dispatch status change event
            $this->dispatch('user-status-changed', [
                'user_id' => $user->id,
                'status' => 'online'
            ])->to('messages');
        }

        // Verificar se há uma conversa para abrir (vindo de uma notificação)
        if (session()->has('open_conversation')) {
            $conversationId = session()->get('open_conversation');
            $this->selectConversation($conversationId);

            // Limpar a sessão para não reabrir a mesma conversa em recargas futuras
            session()->forget('open_conversation');
        }
    }

    public function handleMessageReceived($event)
    {
        // Verificar se o evento é para o usuário atual
        if (isset($event['receiver_id']) && $event['receiver_id'] != Auth::id()) {
            return;
        }

        // Verificar se o usuário atual é o remetente (não mostrar notificação para quem envia)
        if (isset($event['sender_id']) && $event['sender_id'] == Auth::id()) {
            // Se for o remetente, apenas recarregar conversas e fazer scroll
            $this->loadConversations();
            $this->dispatch('scrollToBottom');
            return;
        }

        // Recarregar conversas quando uma nova mensagem é recebida
        $this->loadConversations();

        // Se a conversa atual for a mesma da mensagem recebida, marcar como lida
        if ($this->currentConversationId && isset($event['conversation_id']) && $this->currentConversationId == $event['conversation_id']) {
            Message::where('conversation_id', $event['conversation_id'])
                ->where('sender_id', '!=', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        } else {
            // Se não estiver na conversa atual, mostrar notificação toast
            $message = Message::with(['sender', 'sender.userPhotos'])->find($event['message_id'] ?? null);
            if ($message && $message->sender_id != Auth::id()) {
                $senderName = $message->sender->name ?? 'Alguém';
                $messagePreview = \Illuminate\Support\Str::limit($message->body, 50);

                // Disparar notificação toast usando o componente ToastNotification
                logger()->debug('Enviando notificação de mensagem para ToastNotification', [
                    'sender' => $senderName,
                    'preview' => $messagePreview,
                    'receiver_id' => Auth::id()
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

        // Forçar um refresh do componente
        $this->dispatch('refresh');

        // Forçar um scroll para o final
        $this->dispatch('scrollToBottom');
    }

    public function handleUserStatusChanged($event)
    {
        // Verificar se o evento contém dados válidos
        if (!isset($event['user_id']) || !isset($event['status'])) {
            return;
        }

        // Atualizar status do usuário na lista de conversas
        $this->loadUsers();
        $this->loadConversations();
    }

    public function loadConversations()
    {
        // Obter todas as conversas do usuário atual
        $user = Auth::user();

        $conversationData = [];

        // Obter todas as conversas em que o usuário é participante
        $userConversations = Conversation::whereHas('participants', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('participants')->get();

        foreach ($userConversations as $conversation) {
            // Para conversas 1:1, obter o outro participante
            if (!$conversation->is_group) {
                $otherUser = $conversation->participants->where('id', '!=', $user->id)->first();

                if ($otherUser) {
                    // Obter a última mensagem
                    $latestMessage = $conversation->messages()->latest()->first();

                    // Contar mensagens não lidas
                    $unreadCount = $conversation->messages()
                        ->where('sender_id', $otherUser->id)
                        ->where('receiver_id', $user->id)
                        ->whereNull('read_at')
                        ->count();

                    $conversationData[] = [
                        'id' => $conversation->id,
                        'user' => $otherUser,
                        'latest_message' => $latestMessage,
                        'unread_count' => $unreadCount
                    ];
                }
            }
        }

        // Ordenar por última mensagem
        usort($conversationData, function($a, $b) {
            // Se ambos não tiverem mensagens, manter a ordem original
            if (!isset($a['latest_message']) && !isset($b['latest_message'])) {
                return 0;
            }

            // Se apenas um não tiver mensagens, o que tem mensagem vem primeiro
            if (!isset($a['latest_message'])) {
                return 1; // b vem antes de a
            }

            if (!isset($b['latest_message'])) {
                return -1; // a vem antes de b
            }

            // Se ambos tiverem mensagens, ordenar pela data mais recente
            return $b['latest_message']->created_at <=> $a['latest_message']->created_at;
        });

        $this->conversations = $conversationData;

        // Calcular total de mensagens não lidas
        $this->unreadCount = array_sum(array_column($conversationData, 'unread_count'));
    }

    public function loadUsers()
    {
        $this->users = User::where('id', '!=', Auth::id())
            ->when($this->searchTerm, function ($query) {
                return $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('username', 'like', '%' . $this->searchTerm . '%');
            })
            ->with('userPhotos')
            ->get();
    }

    public function selectConversation($userId)
    {
        $this->selectedConversation = $userId;

        // Encontrar ou criar conversa
        $conversation = $this->findOrCreateConversation($userId);
        $this->currentConversationId = $conversation->id;

        // Marcar mensagens como lidas
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->loadConversations();
    }

    protected function findOrCreateConversation($userId)
    {
        $user = Auth::user();

        // Verificar se já existe uma conversa entre os usuários
        $conversation = Conversation::whereHas('participants', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereHas('participants', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('is_group', false)->first();

        // Se não existir, criar uma nova conversa
        if (!$conversation) {
            $conversation = Conversation::create(['is_group' => false]);
            $conversation->participants()->attach([$user->id, $userId]);
        }

        return $conversation;
    }

    public function getMessagesProperty()
    {
        if (!$this->currentConversationId) {
            return collect();
        }

        return Message::where('conversation_id', $this->currentConversationId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage()
    {
        if (!$this->selectedConversation || empty(trim($this->messageBody))) {
            return;
        }

        $user = Auth::user();
        $conversation = $this->findOrCreateConversation($this->selectedConversation);

        // Verificar se o destinatário está em modo "Não Perturbe"
        $receiver = User::find($this->selectedConversation);
        $isDnd = $receiver && $receiver->status === 'dnd';

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $this->selectedConversation,
            'conversation_id' => $conversation->id,
            'body' => $this->messageBody,
        ]);

        // Create notification for the recipient apenas se não estiver em modo "Não Perturbe"
        if (!$isDnd) {
            Notification::create([
                'user_id' => $this->selectedConversation,
                'sender_id' => $user->id,
                'type' => 'message',
                'message' => 'Você recebeu uma nova mensagem'
            ]);

            // Em vez de usar o evento broadcast, vamos usar o dispatch do Livewire 3
            // para notificar diretamente o componente do destinatário
            $this->dispatch('message-received', [
                'conversation_id' => $conversation->id,
                'message_id' => $message->id,
                'sender_id' => $user->id,
                'receiver_id' => $this->selectedConversation
            ])->to('messages');

            // Não precisamos enviar notificação toast aqui
            // A notificação deve aparecer apenas para quem recebe a mensagem
            // O evento message-received já é suficiente para notificar o destinatário
        }

        $this->messageBody = '';

        // Forçar um refresh do componente
        $this->dispatch('refresh');

        // Forçar um scroll para o final
        $this->dispatch('scrollToBottom');

        // No Livewire 3, usamos dispatch para eventos de browser também
        $this->dispatch('browser-event', ['name' => 'scrollToBottom']);

        $this->loadConversations();

        // Mostrar aviso se o usuário estiver em modo "Não Perturbe"
        if ($isDnd) {
            $this->dispatch('notify', [
                'message' => $receiver->name . ' está em modo "Não Perturbe" e pode não responder imediatamente.',
                'type' => 'warning'
            ]);
        }
    }

    public function startNewConversation($userId)
    {
        $this->selectedConversation = $userId;
        $conversation = $this->findOrCreateConversation($userId);
        $this->currentConversationId = $conversation->id;
    }

    public function updateUserStatus($status)
    {
        $user = Auth::user();
        $user->update([
            'status' => $status,
            'last_seen' => now()
        ]);

        // Dispatch status change event
        $this->dispatch('user-status-changed', [
            'user_id' => $user->id,
            'status' => $status
        ])->to('messages');
    }

    /**
     * Método para forçar o scroll para o final da conversa
     */
    public function scrollToBottom()
    {
        $this->dispatch('scrollToBottom');
    }

    /**
     * Verifica se há novas mensagens não lidas e mostra notificações
     */
    public function checkNewMessages()
    {
        // Armazenar o timestamp da última verificação em uma variável de sessão
        $lastCheck = session('last_message_check', now()->subMinute());

        // Atualizar o timestamp da última verificação
        session(['last_message_check' => now()]);

        // Buscar mensagens não lidas recentes (desde a última verificação)
        // Garantir que sejam apenas mensagens recebidas (receiver_id = usuário atual)
        $newMessages = Message::where('receiver_id', Auth::id())
            ->where('sender_id', '!=', Auth::id()) // Garantir que não são mensagens enviadas pelo próprio usuário
            ->whereNull('read_at')
            ->where('created_at', '>=', $lastCheck)
            ->with(['sender', 'sender.userPhotos'])
            ->get();

        // Para cada mensagem nova, mostrar uma notificação toast
        foreach ($newMessages as $message) {
            // Não mostrar notificação se estiver na conversa atual
            if ($this->currentConversationId && $message->conversation_id == $this->currentConversationId) {
                continue;
            }

            $senderName = $message->sender->name ?? 'Alguém';
            $messagePreview = \Illuminate\Support\Str::limit($message->body, 50);

            // Disparar notificação toast usando o componente ToastNotification
            logger()->debug('Enviando notificação de mensagem para ToastNotification (checkNewMessages)', [
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

    /**
     * Método de teste para enviar uma notificação diretamente
     */
    public function testNotification()
    {
        \Illuminate\Support\Facades\Log::info('Messages: Testando notificação diretamente do componente');

        // Tipos de notificações para testar
        $types = ['success', 'error', 'info', 'message'];
        $type = $types[array_rand($types)];

        // Mensagens de teste para cada tipo
        $messages = [
            'success' => 'Mensagem enviada com sucesso!',
            'error' => 'Erro ao enviar mensagem.',
            'info' => 'Você tem novas mensagens não lidas.',
            'message' => 'Nova mensagem recebida de um usuário.'
        ];

        // Usar o componente de toast para notificação
        $this->dispatch('toast',
            message: $messages[$type],
            type: $type,
            timeout: 8000
        )->to('toast-notification');

        // Também disparar evento de notificação para Alpine.js
        $this->dispatch('notify', [
            'message' => $messages[$type],
            'type' => $type,
            'timeout' => 8000
        ]);

        // Mostrar mensagem de confirmação
        session()->flash('message', 'Notificação de teste enviada!');
    }

    /**
     * Método para testar notificação diretamente via JavaScript
     */
    public function directToast($message, $type = 'message', $timeout = 5000, $avatar = null, $senderId = null)
    {
        // Obter avatar padrão se não for fornecido
        if (!$avatar) {
            $avatar = asset('vendor/fluxui/img/avatar.jpg');
        }

        // Tentar diferentes abordagens para garantir que a notificação seja exibida

        // 1. Disparar notificação toast usando o componente ToastNotification
        $this->dispatch('toast',
            message: $message,
            type: $type,
            timeout: $timeout,
            avatar: $avatar,
            senderId: $senderId
        )->to('toast-notification');

        // 2. Disparar evento global para qualquer componente que esteja ouvindo
        $this->dispatch('toast',
            message: $message,
            type: $type,
            timeout: $timeout,
            avatar: $avatar,
            senderId: $senderId
        );

        // 3. Disparar evento de notificação para Alpine.js
        $this->dispatch('notify', [
            'message' => $message,
            'type' => $type,
            'timeout' => $timeout,
            'avatar' => $avatar,
            'senderId' => $senderId
        ]);

        // 4. Disparar evento para o browser
        $this->dispatch('browser-event', [
            'name' => 'showToast',
            'data' => [
                'message' => $message,
                'type' => $type,
                'timeout' => $timeout,
                'avatar' => $avatar,
                'senderId' => $senderId
            ]
        ]);

        // Registrar no log para debug
        logger()->debug('Notificação direta enviada via JavaScript', [
            'message' => $message,
            'type' => $type,
            'avatar' => $avatar
        ]);
    }

    public function render()
    {
        // Não precisamos mais verificar novas mensagens aqui
        // O componente MessageNotifier já faz isso em todas as páginas
        // $this->checkNewMessages();

        // Emitir evento para rolar para o final após renderização
        $this->dispatch('scrollToBottom');

        return view('livewire.messages', [
            'messages' => $this->messages
        ]);
    }

    /**
     * Método para testar notificação diretamente da página de mensagens
     * Este método pode ser chamado via JavaScript para testar notificações
     */
    public function testMessageNotification()
    {
        // Buscar um usuário diferente do atual para simular uma mensagem recebida
        $sender = User::where('id', '!=', Auth::id())->first();

        if (!$sender) {
            // Se não encontrar outro usuário, usar o próprio usuário (apenas para teste)
            $sender = Auth::user();
        }

        $senderName = $sender->name;
        $messagePreview = "Esta é uma mensagem de teste em tempo real";

        // Obter avatar do remetente ou usar avatar padrão
        $avatar = null;
        if ($sender->userPhotos->first()) {
            $avatar = asset($sender->userPhotos->first()->photo_path);
        } else {
            // Usar avatar padrão do Flux UI
            $avatar = asset('vendor/fluxui/img/avatar.jpg');
        }

        // Disparar notificação toast usando o componente ToastNotification
        $this->dispatch('toast',
            message: "{$senderName}: {$messagePreview}",
            type: 'message',
            timeout: 5000,
            avatar: $avatar,
            senderId: $sender->id
        )->to('toast-notification');

        // Também disparar evento de notificação para Alpine.js
        $this->dispatch('notify', [
            'message' => "{$senderName}: {$messagePreview}",
            'type' => 'message',
            'timeout' => 5000,
            'avatar' => $sender->userPhotos->first() ? asset($sender->userPhotos->first()->photo_path) : null,
            'senderId' => $sender->id
        ]);

        // Registrar no log para debug
        logger()->debug('Teste de notificação de mensagem enviado', [
            'sender' => $senderName,
            'receiver' => Auth::user()->name,
            'preview' => $messagePreview
        ]);
    }
}
