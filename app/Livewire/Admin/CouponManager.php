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
    public $active = true;
    public $expiresAt;
    public $usageLimit;
    

    // Controle de modal
    public $showModal = false;
    public $confirmingDelete = false;
    public $deleteId;
    public $isEditing = false;

    protected $rules = [
        'code'        => 'required|string|unique:coupons,code',
        'type'        => 'required|in:percentage,fixed',
        'value'       => 'required|numeric|min:0',
        'expiresAt'   => 'nullable|date',
        'usageLimit'  => 'nullable|integer|min:0',        
        'active'      => 'boolean',
    ];

    // Livewire 3 - Propriedades que podem ser atualizadas
    protected function getListeners()
    {
        return [
            'save' => 'save',
            'delete' => 'delete',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingActiveFilter()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        // Se o tipo for alterado para 'fixed', limpa o desconto máximo
        if ($this->type === 'fixed') {
            $this->maxDiscount = null;
        }
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
        $this->showModal = true;
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->couponId    = $coupon->id;
        $this->code        = $coupon->code;
        $this->type        = $coupon->type;
        $this->value       = $coupon->value;
        $this->expiresAt   = optional($coupon->expires_at)->format('Y-m-d');
        $this->usageLimit  = $coupon->usage_limit;
        $this->active      = $coupon->active;
        $this->isEditing   = true;
        $this->showModal   = true;
    }

    public function save()
    {
        // Se for edição, ignora a própria linha na unique
        if ($this->isEditing) {
            $this->rules['code'] = 'required|string|unique:coupons,code,' . $this->couponId;
        }

        $data = $this->validate();

        Coupon::updateOrCreate(
            ['id' => $this->couponId],
            [
                'code'          => $data['code'],
                'type'          => $data['type'],
                'value'         => $data['value'],
                'expires_at'    => $data['expiresAt'] ?? null,
                'usage_limit'   => $data['usageLimit'] ?? null,
                'active'        => $data['active'],
            ]
        );

        session()->flash('message', $this->isEditing
            ? 'Cupom atualizado com sucesso.'
            : 'Cupom criado com sucesso.'
        );

        $this->resetForm();
        $this->showModal = false;
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
        $this->reset([
            'couponId',
            'code',
            'type',
            'value',
            'expiresAt',
            'usageLimit',
            'active',
            'isEditing',
        ]);

        // opcional: resetar página de paginação se necessário
        $this->resetPage();
    }

    public function render()
    {
        $query = Coupon::query()
            ->when($this->search, fn($q) => $q->where('code', 'like', "%{$this->search}%"))
            ->when($this->activeFilter !== '', fn($q) => $q->where('active', $this->activeFilter))
            ->orderBy($this->sortBy, $this->sortDirection);

        $coupons = $query->paginate($this->perPage);

        return view('livewire.admin.coupon-manager', compact('coupons'));
    }
}
