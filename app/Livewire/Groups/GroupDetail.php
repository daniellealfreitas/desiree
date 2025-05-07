<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\UserPoint;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class GroupDetail extends Component
{
    use WithPagination;

    public $group;
    public $tab = 'posts';
    public $showJoinConfirmation = false;
    public $showLeaveConfirmation = false;

    public function mount(Group $group)
    {
        $this->group = $group;
        
        // Check if the user can view this group
        if ($this->group->privacy === 'secret' && !Auth::check()) {
            abort(404);
        }
        
        if ($this->group->privacy === 'secret' && !Auth::user()->isMemberOf($this->group)) {
            abort(403, 'Você não tem permissão para visualizar este grupo.');
        }
    }

    public function changeTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function confirmJoin()
    {
        $this->showJoinConfirmation = true;
    }

    public function confirmLeave()
    {
        $this->showLeaveConfirmation = true;
    }

    public function join()
    {
        $user = Auth::user();
        
        // Check if the user is already a member
        if ($user->isMemberOf($this->group)) {
            session()->flash('info', 'Você já é membro deste grupo.');
            $this->showJoinConfirmation = false;
            return;
        }
        
        // Check if the group is private and requires approval
        if ($this->group->privacy === 'private') {
            $user->groups()->attach($this->group->id, [
                'role' => 'member',
                'is_approved' => false,
                'joined_at' => now(),
            ]);
            
            session()->flash('info', 'Sua solicitação para entrar no grupo foi enviada e está aguardando aprovação.');
            $this->showJoinConfirmation = false;
            return;
        }
        
        // For public groups, join immediately
        $user->groups()->attach($this->group->id, [
            'role' => 'member',
            'is_approved' => true,
            'joined_at' => now(),
        ]);
        
        // Increment the members count
        $this->group->increment('members_count');
        
        // Add points for joining a group
        UserPoint::addPoints(
            $user->id,
            'group_joined',
            5,
            "Entrou no grupo: {$this->group->name}",
            $this->group->id,
            Group::class
        );
        
        session()->flash('success', 'Você entrou no grupo com sucesso!');
        $this->showJoinConfirmation = false;
        
        // Refresh the group data
        $this->group = Group::find($this->group->id);
    }

    public function leave()
    {
        $user = Auth::user();
        
        // Check if the user is a member
        if (!$user->isMemberOf($this->group)) {
            session()->flash('error', 'Você não é membro deste grupo.');
            $this->showLeaveConfirmation = false;
            return;
        }
        
        // Check if the user is the creator
        if ($this->group->creator_id === $user->id) {
            session()->flash('error', 'Você é o criador deste grupo e não pode sair. Transfira a propriedade para outro membro ou exclua o grupo.');
            $this->showLeaveConfirmation = false;
            return;
        }
        
        // Remove the user from the group
        $user->groups()->detach($this->group->id);
        
        // Decrement the members count
        $this->group->decrement('members_count');
        
        session()->flash('success', 'Você saiu do grupo com sucesso!');
        $this->showLeaveConfirmation = false;
        
        // Redirect to groups index
        return redirect()->route('grupos.index');
    }

    public function render()
    {
        $isMember = Auth::check() ? Auth::user()->isMemberOf($this->group) : false;
        $isAdmin = Auth::check() ? Auth::user()->isAdminOf($this->group) : false;
        $isModerator = Auth::check() ? Auth::user()->isModeratorOf($this->group) : false;
        
        $posts = $this->group->posts()
                            ->with(['user', 'likes', 'comments'])
                            ->latest()
                            ->paginate(10);
                            
        $members = $this->group->members()
                              ->take(8)
                              ->get();
                              
        return view('livewire.groups.group-detail', [
            'posts' => $posts,
            'members' => $members,
            'isMember' => $isMember,
            'isAdmin' => $isAdmin,
            'isModerator' => $isModerator,
        ]);
    }
}
