<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;
use App\Models\Notification;
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

    // Livewire 3 usa o método getListeners() em vez de $listeners
    protected function getListeners()
    {
        return ['messageReceived' => 'loadConversations'];
    }

    public function mount()
    {
        $this->loadConversations();
        $this->loadUsers();
    }

    public function loadConversations()
    {
        // Get all users that the current user has exchanged messages with
        $sentMessages = Message::where('sender_id', Auth::id())
            ->select('receiver_id as user_id')
            ->distinct()
            ->get()
            ->pluck('user_id');

        $receivedMessages = Message::where('receiver_id', Auth::id())
            ->select('sender_id as user_id')
            ->distinct()
            ->get()
            ->pluck('user_id');

        $userIds = $sentMessages->merge($receivedMessages)->unique();

        // Get the latest message for each conversation
        $this->conversations = [];
        foreach ($userIds as $userId) {
            $latestMessage = Message::where(function ($query) use ($userId) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $userId);
            })->orWhere(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', Auth::id());
            })
            ->with('sender', 'receiver')
            ->latest()
            ->first();

            if ($latestMessage) {
                $otherUser = $latestMessage->sender_id == Auth::id()
                    ? $latestMessage->receiver
                    : $latestMessage->sender;

                $unreadCount = Message::where('sender_id', $userId)
                    ->where('receiver_id', Auth::id())
                    ->whereNull('read_at')
                    ->count();

                $this->conversations[] = [
                    'user' => $otherUser,
                    'latest_message' => $latestMessage,
                    'unread_count' => $unreadCount
                ];
            }
        }

        // Sort conversations by latest message
        usort($this->conversations, function ($a, $b) {
            return $b['latest_message']->created_at <=> $a['latest_message']->created_at;
        });

        // Calculate total unread messages
        $this->unreadCount = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();
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

        // Mark messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->loadConversations();
    }

    public function getMessagesProperty()
    {
        if (!$this->selectedConversation) {
            return collect();
        }

        return Message::where(function ($query) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $this->selectedConversation);
            })->orWhere(function ($query) {
                $query->where('sender_id', $this->selectedConversation)
                    ->where('receiver_id', Auth::id());
            })
            ->with('sender', 'receiver')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage()
    {
        if (!$this->selectedConversation || empty(trim($this->messageBody))) {
            return;
        }

        // Verificar se o destinatário está em modo "Não Perturbe"
        $receiver = User::find($this->selectedConversation);
        $isDnd = $receiver && $receiver->presence_status === 'dnd';

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedConversation,
            'body' => $this->messageBody,
        ]);

        // Create notification for the recipient apenas se não estiver em modo "Não Perturbe"
        if (!$isDnd) {
            Notification::create([
                'user_id' => $this->selectedConversation,
                'sender_id' => Auth::id(),
                'type' => 'message',
                'message' => 'Você recebeu uma nova mensagem'
            ]);
        }

        $this->messageBody = '';
        $this->loadConversations();

        // Dispatch event for real-time updates
        $this->dispatch('messageReceived');

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
    }

    public function render()
    {
        return view('livewire.messages', [
            'messages' => $this->messages
        ]);
    }
}
