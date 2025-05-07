<?php

namespace App\Livewire\Shop;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class UserOrders extends Component
{
    use WithPagination;
    
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    
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
        $query = Order::where('user_id', auth()->id());
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhere('payment_id', 'like', '%' . $this->search . '%')
                  ->orWhere('tracking_number', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        
        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.shop.user-orders', [
            'orders' => $orders
        ]);
    }
}
