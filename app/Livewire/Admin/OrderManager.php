<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';

    // Detalhes do pedido
    public $viewingOrder = null;
    public $showOrderDetails = false;

    // Atualização de status
    public $editingOrder = null;
    public $showStatusModal = false;
    public $newStatus = '';
    public $trackingNumber = '';
    public $statusNote = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewOrder($id)
    {
        $this->viewingOrder = Order::with(['items.product', 'user'])->findOrFail($id);
        $this->showOrderDetails = true;
    }

    public function editStatus($id)
    {
        $this->editingOrder = Order::findOrFail($id);
        $this->newStatus = $this->editingOrder->status;
        $this->trackingNumber = $this->editingOrder->tracking_number;
        $this->statusNote = '';
        $this->showStatusModal = true;
    }

    public function updateStatus()
    {
        $this->validate([
            'newStatus' => 'required|in:' . implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
                Order::STATUS_REFUNDED,
            ]),
            'trackingNumber' => 'nullable|string|max:100',
            'statusNote' => 'nullable|string|max:500',
        ]);

        try {
            $this->editingOrder->status = $this->newStatus;
            
            if ($this->trackingNumber) {
                $this->editingOrder->tracking_number = $this->trackingNumber;
            }
            
            // Adicionar nota ao pedido (opcional - poderia ser implementado em uma tabela separada)
            if ($this->statusNote) {
                $notes = $this->editingOrder->notes ?? '';
                $notes .= "\n" . now()->format('d/m/Y H:i') . " - Status alterado para {$this->newStatus}: {$this->statusNote}";
                $this->editingOrder->notes = trim($notes);
            }
            
            $this->editingOrder->save();
            
            $this->showStatusModal = false;
            $this->editingOrder = null;
            
            $this->dispatch('notify', [
                'message' => 'Status do pedido atualizado com sucesso!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao atualizar status: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function getStatusClass($status)
    {
        return match ($status) {
            Order::STATUS_PENDING => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
            Order::STATUS_PROCESSING => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
            Order::STATUS_COMPLETED => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            Order::STATUS_SHIPPED => 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200',
            Order::STATUS_DELIVERED => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            Order::STATUS_CANCELLED => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            Order::STATUS_REFUNDED => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200',
            default => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200',
        };
    }

    public function getStatusName($status)
    {
        return match ($status) {
            Order::STATUS_PENDING => 'Pendente',
            Order::STATUS_PROCESSING => 'Processando',
            Order::STATUS_COMPLETED => 'Concluído',
            Order::STATUS_SHIPPED => 'Enviado',
            Order::STATUS_DELIVERED => 'Entregue',
            Order::STATUS_CANCELLED => 'Cancelado',
            Order::STATUS_REFUNDED => 'Reembolsado',
            default => 'Desconhecido',
        };
    }

    public function render()
    {
        $query = Order::with('user');
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhere('payment_id', 'like', '%' . $this->search . '%')
                  ->orWhere('tracking_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
        
        $query->orderBy($this->sortBy, $this->sortDirection);
        
        $orders = $query->paginate($this->perPage);
        
        return view('livewire.admin.order-manager', [
            'orders' => $orders,
        ]);
    }
}
