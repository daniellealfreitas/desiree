<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class ToastNotification extends Component
{
    public $notifications = [];
    public $testMessage = "Clique para testar notificações";

    public function mount()
    {
        Log::info('ToastNotification: Componente montado');
    }

    #[On('toast')]
    public function showToast($message, $type = 'info', $timeout = 5000, $avatar = null, $senderId = null)
    {
        // Log para debug
        Log::info('ToastNotification: Recebendo notificação', [
            'message' => $message,
            'type' => $type
        ]);

        // Verificar se já existe uma notificação idêntica recente (nos últimos 2 segundos)
        $now = now()->timestamp;
        $recentDuplicate = false;

        foreach ($this->notifications as $notification) {
            // Verificar se é uma notificação idêntica e recente
            if ($notification['message'] === $message &&
                $notification['type'] === $type &&
                ($now - $notification['timestamp']) < 2) {
                $recentDuplicate = true;
                break;
            }
        }

        // Se for uma duplicata recente, não adicionar
        if ($recentDuplicate) {
            Log::info('ToastNotification: Ignorando notificação duplicada', [
                'message' => $message
            ]);
            return;
        }

        // Adicionar notificação à lista
        $this->notifications[] = [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type,
            'timeout' => $timeout,
            'avatar' => $avatar,
            'sender_id' => $senderId,
            'timestamp' => $now
        ];

        // Limitar a 5 notificações para não sobrecarregar a tela
        if (count($this->notifications) > 5) {
            array_shift($this->notifications);
        }

        // Forçar atualização da view
        $this->dispatch('toast-added');
    }

    // Método alternativo para receber notificações (sem atributo)
    public function receiveToast($message, $type = 'info', $timeout = 5000, $avatar = null, $senderId = null)
    {
        $this->showToast($message, $type, $timeout, $avatar, $senderId);
    }

    public function removeToast($id)
    {
        Log::info('ToastNotification: Removendo notificação', ['id' => $id]);

        $this->notifications = array_filter($this->notifications, function($notification) use ($id) {
            return $notification['id'] !== $id;
        });
    }

    /**
     * Limpa todas as notificações
     */
    public function clearAllToasts()
    {
        Log::info('ToastNotification: Limpando todas as notificações');
        $this->notifications = [];
    }

    public function goToMessages($senderId = null)
    {
        if ($senderId) {
            // Redirecionar para a conversa específica com o remetente
            return redirect()->route('caixa_de_mensagens')->with('open_conversation', $senderId);
        }

        // Redirecionar para a página de mensagens geral
        return redirect()->route('caixa_de_mensagens');
    }

    /**
     * Método de teste para enviar uma notificação diretamente
     */
    public function testToast()
    {
        Log::info('ToastNotification: Testando toast diretamente');

        $this->testMessage = "Notificação de teste enviada!";

        // Tipos de notificações para testar
        $types = ['success', 'error', 'info', 'message'];
        $type = $types[array_rand($types)];

        // Mensagens de teste para cada tipo
        $messages = [
            'success' => 'Operação realizada com sucesso!',
            'error' => 'Ocorreu um erro ao processar sua solicitação.',
            'info' => 'Informação importante para você.',
            'message' => 'Nova mensagem recebida de um usuário.'
        ];

        // Adicionar notificação diretamente
        $this->notifications[] = [
            'id' => uniqid(),
            'message' => $messages[$type],
            'type' => $type,
            'timeout' => 5000,
            'avatar' => null,
            'sender_id' => null,
            'timestamp' => now()->timestamp
        ];

        // Limitar a 5 notificações
        if (count($this->notifications) > 5) {
            array_shift($this->notifications);
        }

        // Forçar atualização da view
        $this->dispatch('toast-added');
    }

    /**
     * Método para forçar a atualização do componente
     */
    public function refreshComponent()
    {
        // Forçar atualização do componente
        $this->dispatch('refresh');
    }

    public function render()
    {
        // Verificar se há notificações para exibir
        if (count($this->notifications) > 0) {
            Log::info('ToastNotification: Renderizando com ' . count($this->notifications) . ' notificações');
        }

        return view('livewire.toast-notification');
    }
}
