<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\User;
use App\Models\GroupInvitation;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class GroupInvitations extends Component
{
    use WithPagination;

    public $group;
    public $search = '';
    public $selectedUser = null;
    public $showInviteModal = false;

    protected $queryString = ['search'];

    public function mount(Group $group)
    {
        $this->group = $group;

        // Check if the user is a member of the group
        if (!Auth::user()->isMemberOf($this->group)) {
            abort(403, 'Você não tem permissão para convidar membros para este grupo.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openInviteModal($userId)
    {
        // Carrega o usuário com todas as relações necessárias
        $this->selectedUser = User::with(['userPhotos'])->find($userId);

        if (!$this->selectedUser) {
            session()->flash('error', 'Usuário não encontrado.');
            return;
        }

        $this->showInviteModal = true;
    }

    public function invite()
    {
        // Verifica se o usuário selecionado existe
        if (!$this->selectedUser) {
            session()->flash('error', 'Usuário não encontrado.');
            $this->showInviteModal = false;
            return;
        }

        // Check if the user is a member of the group
        if (!Auth::user()->isMemberOf($this->group)) {
            session()->flash('error', 'Você não tem permissão para convidar membros para este grupo.');
            $this->showInviteModal = false;
            return;
        }

        // Check if the selected user is already a member
        if ($this->selectedUser->isMemberOf($this->group)) {
            session()->flash('info', 'Este usuário já é membro do grupo.');
            $this->showInviteModal = false;
            return;
        }

        // Check if there's already a pending invitation
        $existingInvitation = GroupInvitation::where('group_id', $this->group->id)
                                            ->where('user_id', $this->selectedUser->id)
                                            ->where('status', 'pending')
                                            ->first();

        if ($existingInvitation) {
            session()->flash('info', 'Este usuário já possui um convite pendente para este grupo.');
            $this->showInviteModal = false;
            return;
        }

        // Create the invitation
        $invitation = GroupInvitation::create([
            'group_id' => $this->group->id,
            'user_id' => $this->selectedUser->id,
            'invited_by' => Auth::id(),
            'status' => 'pending',
        ]);

        // Create a notification for the invited user
        Notification::create([
            'user_id' => $this->selectedUser->id,
            'sender_id' => Auth::id(),
            'type' => 'group_invitation',
            'group_id' => $this->group->id,
            'message' => 'Você foi convidado para o grupo ' . $this->group->name . '.'
        ]);

        session()->flash('success', 'Convite enviado com sucesso!');
        $this->showInviteModal = false;
    }

    public function cancelInvitation($invitationId)
    {
        $invitation = GroupInvitation::find($invitationId);

        if (!$invitation) {
            session()->flash('error', 'Convite não encontrado.');
            return;
        }

        // Check if the authenticated user is the inviter
        if ($invitation->invited_by !== Auth::id() && !Auth::user()->canManageGroup($this->group)) {
            session()->flash('error', 'Você não tem permissão para cancelar este convite.');
            return;
        }

        // Check if the invitation is still pending
        if ($invitation->status !== 'pending') {
            session()->flash('error', 'Este convite já foi respondido.');
            return;
        }

        // Delete the invitation
        $invitation->delete();

        session()->flash('success', 'Convite cancelado com sucesso!');
    }

    public function render()
    {
        // Get users who are not members of the group and match the search
        $users = User::with(['userPhotos', 'followers'])
                    ->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                              ->orWhere('username', 'like', '%' . $this->search . '%');
                    })
                    ->whereDoesntHave('groups', function ($query) {
                        $query->where('group_id', $this->group->id);
                    })
                    ->paginate(10);

        // Get pending invitations for this group
        $pendingInvitations = GroupInvitation::where('group_id', $this->group->id)
                                            ->where('status', 'pending')
                                            ->with(['user.userPhotos', 'inviter'])
                                            ->get();

        return view('livewire.groups.group-invitations', [
            'users' => $users,
            'pendingInvitations' => $pendingInvitations,
        ]);
    }
}
