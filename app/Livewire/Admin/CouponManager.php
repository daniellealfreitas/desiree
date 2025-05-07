<?php

namespace App\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;
use Livewire\WithPagination;

class CouponManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $activeFilter = '';

    // Campos do formulário
    public $couponId;
    public $code;
    public $type = 'percentage';
    public $value;
    public $description;
    public $active = true;
    public $expiresAt;
    public $usageLimit;
    public $minPurchase;
    public $maxDiscount;

    // Controle de modal
    public $showModal = false;
    public $confirmingDelete = false;
    public $deleteId;
    public $isEditing = false;

    protected $rules = [
        'code' => 'required|string|max:50',
        'type' => 'required|in:percentage,fixed',
        'value' => 'required|numeric|min:0',
        'description' => 'nullable|string|max:255',
        'active' => 'boolean',
        'expiresAt' => 'nullable|date',
        'usageLimit' => 'nullable|integer|min:0',
        'minPurchase' => 'nullable|numeric|min:0',
        'maxDiscount' => 'nullable|numeric|min:0',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingActiveFilter()
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

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->couponId = $id;
        
        $coupon = Coupon::findOrFail($id);
        
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->description = $coupon->description;
        $this->active = $coupon->active;
        $this->expiresAt = $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : null;
        $this->usageLimit = $coupon->usage_limit;
        $this->minPurchase = $coupon->min_purchase;
        $this->maxDiscount = $coupon->max_discount;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        
        try {
            if ($this->isEditing) {
                $coupon = Coupon::findOrFail($this->couponId);
            } else {
                $coupon = new Coupon();
                $coupon->used = 0;
            }
            
            $coupon->code = $this->code;
            $coupon->type = $this->type;
            $coupon->value = $this->value;
            $coupon->description = $this->description;
            $coupon->active = $this->active;
            $coupon->expires_at = $this->expiresAt;
            $coupon->usage_limit = $this->usageLimit;
            $coupon->min_purchase = $this->minPurchase;
            $coupon->max_discount = $this->maxDiscount;
            
            $coupon->save();
            
            $this->showModal = false;
            $this->resetForm();
            
            $this->dispatch('notify', [
                'message' => $this->isEditing ? 'Cupom atualizado com sucesso!' : 'Cupom criado com sucesso!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao salvar cupom: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->deleteId = $id;
    }

    public function delete()
    {
        try {
            $coupon = Coupon::findOrFail($this->deleteId);
            
            // Verificar se o cupom está sendo usado em pedidos
            if ($coupon->orders()->count() > 0) {
                throw new \Exception('Não é possível excluir um cupom que já foi utilizado em pedidos.');
            }
            
            $coupon->delete();
            
            $this->confirmingDelete = false;
            $this->deleteId = null;
            
            $this->dispatch('notify', [
                'message' => 'Cupom excluído com sucesso!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao excluir cupom: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function resetForm()
    {
        $this->couponId = null;
        $this->code = '';
        $this->type = 'percentage';
        $this->value = '';
        $this->description = '';
        $this->active = true;
        $this->expiresAt = null;
        $this->usageLimit = null;
        $this->minPurchase = null;
        $this->maxDiscount = null;
        
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = Coupon::query();
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('code', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->activeFilter !== '') {
            $query->where('active', $this->activeFilter === '1');
        }
        
        $query->orderBy($this->sortBy, $this->sortDirection);
        
        $coupons = $query->paginate($this->perPage);
        
        return view('livewire.admin.coupon-manager', [
            'coupons' => $coupons,
        ])->layout('layouts.admin', ['title' => 'Gerenciar Cupons']);
    }
}
