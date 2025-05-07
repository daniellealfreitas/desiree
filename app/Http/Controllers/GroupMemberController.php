<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMemberController extends Controller
{
    /**
     * Display a listing of the group members.
     */
    public function index(Group $group)
    {
        // Check if the user can view members
        if ($group->privacy === 'secret' && !Auth::user()->isMemberOf($group)) {
            abort(403, 'Você não tem permissão para visualizar os membros deste grupo.');
        }
        
        $members = $group->members()->paginate(20);
        $pendingMembers = Auth::user()->canManageGroup($group) 
                        ? $group->members()->wherePivot('is_approved', false)->get() 
                        : collect();
        
        return view('groups.members.index', compact('group', 'members', 'pendingMembers'));
    }

    /**
     * Approve a member's request to join the group.
     */
    public function approve(Group $group, User $user)
    {
        // Check if the authenticated user can manage the group
        if (!Auth::user()->canManageGroup($group)) {
            abort(403, 'Você não tem permissão para aprovar membros neste grupo.');
        }
        
        // Update the membership status
        $group->members()->updateExistingPivot($user->id, [
            'is_approved' => true,
            'joined_at' => now(),
        ]);
        
        // Increment the members count
        $group->increment('members_count');
        
        // Create a notification for the approved user
        Notification::create([
            'user_id' => $user->id,
            'sender_id' => Auth::id(),
            'type' => 'group_request_approved',
            'group_id' => $group->id,
            'message' => 'Sua solicitação para entrar no grupo ' . $group->name . ' foi aprovada.'
        ]);
        
        return redirect()->route('grupos.members.index', $group->slug)
                         ->with('success', 'Membro aprovado com sucesso!');
    }

    /**
     * Reject a member's request to join the group.
     */
    public function reject(Group $group, User $user)
    {
        // Check if the authenticated user can manage the group
        if (!Auth::user()->canManageGroup($group)) {
            abort(403, 'Você não tem permissão para rejeitar membros neste grupo.');
        }
        
        // Remove the user from the group
        $group->members()->detach($user->id);
        
        // Create a notification for the rejected user
        Notification::create([
            'user_id' => $user->id,
            'sender_id' => Auth::id(),
            'type' => 'group_request_rejected',
            'group_id' => $group->id,
            'message' => 'Sua solicitação para entrar no grupo ' . $group->name . ' foi rejeitada.'
        ]);
        
        return redirect()->route('grupos.members.index', $group->slug)
                         ->with('success', 'Solicitação rejeitada com sucesso!');
    }

    /**
     * Remove a member from the group.
     */
    public function remove(Group $group, User $user)
    {
        // Check if the authenticated user can manage the group
        if (!Auth::user()->canManageGroup($group)) {
            abort(403, 'Você não tem permissão para remover membros deste grupo.');
        }
        
        // Check if the user to be removed is the creator
        if ($user->id === $group->creator_id) {
            return redirect()->route('grupos.members.index', $group->slug)
                             ->with('error', 'Você não pode remover o criador do grupo.');
        }
        
        // Check if the user to be removed is an admin and the authenticated user is not
        if ($user->isAdminOf($group) && !Auth::user()->isAdminOf($group)) {
            return redirect()->route('grupos.members.index', $group->slug)
                             ->with('error', 'Você não pode remover um administrador do grupo.');
        }
        
        // Remove the user from the group
        $group->members()->detach($user->id);
        
        // Decrement the members count
        $group->decrement('members_count');
        
        // Create a notification for the removed user
        Notification::create([
            'user_id' => $user->id,
            'sender_id' => Auth::id(),
            'type' => 'group_removed',
            'group_id' => $group->id,
            'message' => 'Você foi removido do grupo ' . $group->name . '.'
        ]);
        
        return redirect()->route('grupos.members.index', $group->slug)
                         ->with('success', 'Membro removido com sucesso!');
    }

    /**
     * Change a member's role in the group.
     */
    public function changeRole(Request $request, Group $group, User $user)
    {
        // Check if the authenticated user is an admin of the group
        if (!Auth::user()->isAdminOf($group)) {
            abort(403, 'Você não tem permissão para alterar funções neste grupo.');
        }
        
        $request->validate([
            'role' => 'required|in:member,moderator,admin',
        ]);
        
        // Check if the user to be changed is the creator
        if ($user->id === $group->creator_id) {
            return redirect()->route('grupos.members.index', $group->slug)
                             ->with('error', 'Você não pode alterar a função do criador do grupo.');
        }
        
        // Update the user's role
        $group->members()->updateExistingPivot($user->id, [
            'role' => $request->role,
        ]);
        
        // Create a notification for the user
        Notification::create([
            'user_id' => $user->id,
            'sender_id' => Auth::id(),
            'type' => 'group_role_changed',
            'group_id' => $group->id,
            'message' => 'Sua função no grupo ' . $group->name . ' foi alterada para ' . $request->role . '.'
        ]);
        
        return redirect()->route('grupos.members.index', $group->slug)
                         ->with('success', 'Função do membro alterada com sucesso!');
    }
}
