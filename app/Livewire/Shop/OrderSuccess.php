<?php

namespace App\Livewire\Shop;

use App\Models\Order;
use Livewire\Component;

class OrderSuccess extends Component
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
    
    public function render()
    {
        return view('livewire.shop.order-success');
    }
}
