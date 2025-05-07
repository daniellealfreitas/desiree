<?php

namespace App\Livewire\Shop;

use App\Models\Order;
use Livewire\Component;

class OrderDetail extends Component
{
    public $order;
    
    public function mount($id)
    {
        $this->order = Order::with(['items.product', 'user'])->findOrFail($id);
        
        // Verificar se o pedido pertence ao usuário atual
        if ($this->order->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para acessar este pedido.');
        }
    }
    
    public function getStatusClass($status)
    {
        return match ($status) {
            'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
            'processing' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
            'completed' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            'shipped' => 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200',
            'delivered' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            'cancelled' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            'refunded' => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200',
            default => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200',
        };
    }
    
    public function getStatusName($status)
    {
        return match ($status) {
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'completed' => 'Concluído',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            default => 'Desconhecido',
        };
    }
    
    public function render()
    {
        return view('livewire.shop.order-detail');
    }
}
