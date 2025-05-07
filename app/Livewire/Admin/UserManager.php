<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $roleFilter = '';

    // Campos do formulário
    public $userId;
    public $name;
    public $email;
    public $role = 'user';
    public $active = true;

    // Controle de modal
    public $showModal = false;
    public $confirmingDelete = false;
    public $deleteId;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'role' => 'required|in:user,admin,moderator',
        'active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
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

    public function edit($id)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->userId = $id;
        
        $user = User::findOrFail($id);
        
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->active = $user->active;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        
        try {
            $user = User::findOrFail($this->userId);
            
            $user->name = $this->name;
            $user->email = $this->email;
            $user->role = $this->role;
            $user->active = $this->active;
            
            $user->save();
            
            $this->showModal = false;
            $this->resetForm();
            
            $this->dispatch('notify', [
                'message' => 'Usuário atualizado com sucesso!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao salvar usuário: ' . $e->getMessage(),
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
            $user = User::findOrFail($this->deleteId);
            
            // Não permitir excluir o próprio usuário
            if ($user->id === auth()->id()) {
                throw new \Exception('Você não pode excluir seu próprio usuário.');
            }
            
            // Verificar se o usuário tem pedidos
            if ($user->orders()->count() > 0) {
                throw new \Exception('Não é possível excluir um usuário que possui pedidos.');
            }
            
            $user->delete();
            
            $this->confirmingDelete = false;
            $this->deleteId = null;
            
            $this->dispatch('notify', [
                'message' => 'Usuário excluído com sucesso!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao excluir usuário: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->role = 'user';
        $this->active = true;
        
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = User::query();
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }
        
        $query->orderBy($this->sortBy, $this->sortDirection);
        
        $users = $query->paginate($this->perPage);
        
        return view('livewire.admin.user-manager', [
            'users' => $users,
        ])->layout('layouts.admin', ['title' => 'Gerenciar Usuários']);
    }
}
