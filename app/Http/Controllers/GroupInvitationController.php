<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\GroupInvitation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupInvitationController extends Controller
{
    /**
     * Display a listing of the invitations.
     */
    public function index()
    {
        $pendingInvitations = Auth::user()->groupInvitations()
                                         ->where('status', 'pending')
                                         ->with('group', 'inviter')
                                         ->get();
        
        return view('groups.invitations.index', compact('pendingInvitations'));
    }

    /**
     * Store a newly created invitation in storage.
     */
    public function store(Request $request, Group $group)
    {
        // Check if the authenticated user can invite members
        if (!Auth::user()->isMemberOf($group)) {
            abort(403, 'Você não tem permissão para convidar membros para este grupo.');
        }
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        $user = User::findOrFail($request->user_id);
        
        // Check if the user is already a member
        if ($user->isMemberOf($group)) {
            return redirect()->route('grupos.show', $group->slug)
                             ->with('info', 'Este usuário já é membro do grupo.');
        }
        
        // Check if there's already a pending invitation
        $existingInvitation = GroupInvitation::where('group_id', $group->id)
                                            ->where('user_id', $user->id)
                                            ->where('status', 'pending')
                                            ->first();
        
        if ($existingInvitation) {
            return redirect()->route('grupos.show', $group->slug)
                             ->with('info', 'Este usuário já possui um convite pendente para este grupo.');
        }
        
        // Create the invitation
        $invitation = GroupInvitation::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'invited_by' => Auth::id(),
            'status' => 'pending',
        ]);
        
        // Create a notification for the invited user
        Notification::create([
            'user_id' => $user->id,
            'sender_id' => Auth::id(),
            'type' => 'group_invitation',
            'group_id' => $group->id,
            'message' => 'Você foi convidado para o grupo ' . $group->name . '.'
        ]);
        
        return redirect()->route('grupos.show', $group->slug)
                         ->with('success', 'Convite enviado com sucesso!');
    }

    /**
     * Accept an invitation.
     */
    public function accept(GroupInvitation $invitation)
    {
        // Check if the authenticated user is the invited user
        if ($invitation->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para aceitar este convite.');
        }
        
        // Check if the invitation is still pending
        if ($invitation->status !== 'pending') {
            return redirect()->route('grupos.invitations.index')
                             ->with('error', 'Este convite já foi respondido.');
        }
        
        // Accept the invitation
        $invitation->accept();
        
        return redirect()->route('grupos.show', $invitation->group->slug)
                         ->with('success', 'Você aceitou o convite e agora é membro do grupo!');
    }

    /**
     * Decline an invitation.
     */
    public function decline(GroupInvitation $invitation)
    {
        // Check if the authenticated user is the invited user
        if ($invitation->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para recusar este convite.');
        }
        
        // Check if the invitation is still pending
        if ($invitation->status !== 'pending') {
            return redirect()->route('grupos.invitations.index')
                             ->with('error', 'Este convite já foi respondido.');
        }
        
        // Decline the invitation
        $invitation->decline();
        
        return redirect()->route('grupos.invitations.index')
                         ->with('success', 'Você recusou o convite para o grupo.');
    }

    /**
     * Cancel an invitation.
     */
    public function cancel(GroupInvitation $invitation)
    {
        // Check if the authenticated user is the inviter
        if ($invitation->invited_by !== Auth::id()) {
            abort(403, 'Você não tem permissão para cancelar este convite.');
        }
        
        // Check if the invitation is still pending
        if ($invitation->status !== 'pending') {
            return redirect()->route('grupos.show', $invitation->group->slug)
                             ->with('error', 'Este convite já foi respondido.');
        }
        
        // Delete the invitation
        $invitation->delete();
        
        return redirect()->route('grupos.show', $invitation->group->slug)
                         ->with('success', 'Convite cancelado com sucesso!');
    }
}
