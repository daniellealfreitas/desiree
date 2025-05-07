<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class GroupMembers extends Component
{
    use WithPagination;

    public $group;
    public $search = '';
    public $selectedMember = null;
    public $showRoleModal = false;
    public $showRemoveModal = false;
    public $newRole = 'member';

    protected $queryString = ['search'];

    public function mount(Group $group)
    {
        $this->group = $group;
        
        // Check if the user can view members
        if ($this->group->privacy === 'secret' && !Auth::check()) {
            abort(404);
        }
        
        if ($this->group->privacy === 'secret' && !Auth::user()->isMemberOf($this->group)) {
            abort(403, 'Você não tem permissão para visualizar os membros deste grupo.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openRoleModal(User $member)
    {
        // Check if the authenticated user is an admin of the group
        if (!Auth::user()->isAdminOf($this->group)) {
            session()->flash('error', 'Você não tem permissão para alterar funções neste grupo.');
            return;
        }
        
        $this->selectedMember = $member;
        
        // Get the current role
        $memberRole = $this->group->members()
                                 ->where('user_id', $member->id)
                                 ->first()
                                 ->pivot
                                 ->role;
                                 
        $this->newRole = $memberRole;
        $this->showRoleModal = true;
    }

    public function openRemoveModal(User $member)
    {
        // Check if the authenticated user can manage the group
        if (!Auth::user()->canManageGroup($this->group)) {
            session()->flash('error', 'Você não tem permissão para remover membros deste grupo.');
            return;
        }
        
        $this->selectedMember = $member;
        $this->showRemoveModal = true;
    }

    public function changeRole()
    {
        // Check if the authenticated user is an admin of the group
        if (!Auth::user()->isAdminOf($this->group)) {
            session()->flash('error', 'Você não tem permissão para alterar funções neste grupo.');
            $this->showRoleModal = false;
            return;
        }
        
        // Check if the member to be changed is the creator
        if ($this->selectedMember->id === $this->group->creator_id) {
            session()->flash('error', 'Você não pode alterar a função do criador do grupo.');
            $this->showRoleModal = false;
            return;
        }
        
        // Update the member's role
        $this->group->members()->updateExistingPivot($this->selectedMember->id, [
            'role' => $this->newRole,
        ]);
        
        // Create a notification for the member
        Notification::create([
            'user_id' => $this->selectedMember->id,
            'sender_id' => Auth::id(),
            'type' => 'group_role_changed',
            'group_id' => $this->group->id,
            'message' => 'Sua função no grupo ' . $this->group->name . ' foi alterada para ' . $this->newRole . '.'
        ]);
        
        session()->flash('success', 'Função do membro alterada com sucesso!');
        $this->showRoleModal = false;
    }

    public function removeMember()
    {
        // Check if the authenticated user can manage the group
        if (!Auth::user()->canManageGroup($this->group)) {
            session()->flash('error', 'Você não tem permissão para remover membros deste grupo.');
            $this->showRemoveModal = false;
            return;
        }
        
        // Check if the member to be removed is the creator
        if ($this->selectedMember->id === $this->group->creator_id) {
            session()->flash('error', 'Você não pode remover o criador do grupo.');
            $this->showRemoveModal = false;
            return;
        }
        
        // Check if the member to be removed is an admin and the authenticated user is not
        if ($this->selectedMember->isAdminOf($this->group) && !Auth::user()->isAdminOf($this->group)) {
            session()->flash('error', 'Você não pode remover um administrador do grupo.');
            $this->showRemoveModal = false;
            return;
        }
        
        // Remove the member from the group
        $this->group->members()->detach($this->selectedMember->id);
        
        // Decrement the members count
        $this->group->decrement('members_count');
        
        // Create a notification for the removed member
        Notification::create([
            'user_id' => $this->selectedMember->id,
            'sender_id' => Auth::id(),
            'type' => 'group_removed',
            'group_id' => $this->group->id,
            'message' => 'Você foi removido do grupo ' . $this->group->name . '.'
        ]);
        
        session()->flash('success', 'Membro removido com sucesso!');
        $this->showRemoveModal = false;
    }

    public function approveMember(User $member)
    {
        // Check if the authenticated user can manage the group
        if (!Auth::user()->canManageGroup($this->group)) {
            session()->flash('error', 'Você não tem permissão para aprovar membros neste grupo.');
            return;
        }
        
        // Update the membership status
        $this->group->members()->updateExistingPivot($member->id, [
            'is_approved' => true,
            'joined_at' => now(),
        ]);
        
        // Increment the members count
        $this->group->increment('members_count');
        
        // Create a notification for the approved member
        Notification::create([
            'user_id' => $member->id,
            'sender_id' => Auth::id(),
            'type' => 'group_request_approved',
            'group_id' => $this->group->id,
            'message' => 'Sua solicitação para entrar no grupo ' . $this->group->name . ' foi aprovada.'
        ]);
        
        session()->flash('success', 'Membro aprovado com sucesso!');
    }

    public function rejectMember(User $member)
    {
        // Check if the authenticated user can manage the group
        if (!Auth::user()->canManageGroup($this->group)) {
            session()->flash('error', 'Você não tem permissão para rejeitar membros neste grupo.');
            return;
        }
        
        // Remove the member from the group
        $this->group->members()->detach($member->id);
        
        // Create a notification for the rejected member
        Notification::create([
            'user_id' => $member->id,
            'sender_id' => Auth::id(),
            'type' => 'group_request_rejected',
            'group_id' => $this->group->id,
            'message' => 'Sua solicitação para entrar no grupo ' . $this->group->name . ' foi rejeitada.'
        ]);
        
        session()->flash('success', 'Solicitação rejeitada com sucesso!');
    }

    public function render()
    {
        $canManage = Auth::check() ? Auth::user()->canManageGroup($this->group) : false;
        
        $members = $this->group->members()
                              ->where(function ($query) {
                                  $query->where('name', 'like', '%' . $this->search . '%')
                                        ->orWhere('username', 'like', '%' . $this->search . '%');
                              })
                              ->wherePivot('is_approved', true)
                              ->paginate(20);
                              
        $pendingMembers = $canManage 
                        ? $this->group->members()
                                     ->where(function ($query) {
                                         $query->where('name', 'like', '%' . $this->search . '%')
                                               ->orWhere('username', 'like', '%' . $this->search . '%');
                                     })
                                     ->wherePivot('is_approved', false)
                                     ->get() 
                        : collect();
        
        return view('livewire.groups.group-members', [
            'members' => $members,
            'pendingMembers' => $pendingMembers,
            'canManage' => $canManage,
        ]);
    }
}
