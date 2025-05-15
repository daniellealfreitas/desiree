<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Display a listing of the groups.
     */
    public function index()
    {
        return view('grupos');
    }

    /**
     * Show the form for creating a new group.
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created group in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'privacy' => 'required|in:public,private,secret',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'posts_require_approval' => 'nullable|boolean',
        ]);

        $data = $request->except(['image', 'cover_image']);
        $data['creator_id'] = Auth::id();
        $data['slug'] = Str::slug($request->name);

        // Garante que posts_require_approval seja boolean
        $data['posts_require_approval'] = $request->has('posts_require_approval');

        // Handle image uploads
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('groups/images', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('groups/covers', 'public');
        }

        $group = Group::create($data);

        return redirect()->route('grupos.show', $group->slug)
                         ->with('success', 'Grupo criado com sucesso!');
    }

    /**
     * Display the specified group.
     */
    public function show($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        // Check if the user can view this group
        if ($group->privacy === 'secret' && !Auth::check()) {
            abort(404);
        }

        if ($group->privacy === 'secret' && !Auth::user()->isMemberOf($group)) {
            abort(403, 'Você não tem permissão para visualizar este grupo.');
        }

        $posts = $group->posts()->with(['user', 'likes', 'comments'])->latest()->paginate(10);
        $members = $group->members()->take(8)->get();
        $isMember = Auth::check() ? Auth::user()->isMemberOf($group) : false;

        return view('groups.show', compact('group', 'posts', 'members', 'isMember'));
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Group $group)
    {
        // Check if the user can edit this group
        if (!Auth::user()->canManageGroup($group)) {
            abort(403, 'Você não tem permissão para editar este grupo.');
        }

        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified group in storage.
     */
    public function update(Request $request, Group $group)
    {
        // Check if the user can update this group
        if (!Auth::user()->canManageGroup($group)) {
            abort(403, 'Você não tem permissão para atualizar este grupo.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'privacy' => 'required|in:public,private,secret',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'posts_require_approval' => 'nullable|boolean',
        ]);

        $data = $request->except(['image', 'cover_image']);

        // Garante que posts_require_approval seja boolean
        $data['posts_require_approval'] = $request->has('posts_require_approval');

        // Handle image uploads
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($group->image) {
                Storage::disk('public')->delete($group->image);
            }

            $data['image'] = $request->file('image')->store('groups/images', 'public');
        }

        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($group->cover_image) {
                Storage::disk('public')->delete($group->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('groups/covers', 'public');
        }

        $group->update($data);

        return redirect()->route('grupos.show', $group->slug)
                         ->with('success', 'Grupo atualizado com sucesso!');
    }

    /**
     * Remove the specified group from storage.
     */
    public function destroy(Group $group)
    {
        // Check if the user can delete this group
        if (!Auth::user()->isAdminOf($group)) {
            abort(403, 'Você não tem permissão para excluir este grupo.');
        }

        // Delete group images
        if ($group->image) {
            Storage::disk('public')->delete($group->image);
        }

        if ($group->cover_image) {
            Storage::disk('public')->delete($group->cover_image);
        }

        $group->delete();

        return redirect()->route('grupos.index')
                         ->with('success', 'Grupo excluído com sucesso!');
    }

    /**
     * Join a group.
     */
    public function join(Group $group)
    {
        $user = Auth::user();

        // Check if the user is already a member
        if ($user->isMemberOf($group)) {
            return redirect()->route('grupos.show', $group->slug)
                             ->with('info', 'Você já é membro deste grupo.');
        }

        // Check if the group is private and requires approval
        if ($group->privacy === 'private') {
            $user->groups()->attach($group->id, [
                'role' => 'member',
                'is_approved' => false,
                'joined_at' => now(),
            ]);

            return redirect()->route('grupos.show', $group->slug)
                             ->with('info', 'Sua solicitação para entrar no grupo foi enviada e está aguardando aprovação.');
        }

        // For public groups, join immediately
        $user->groups()->attach($group->id, [
            'role' => 'member',
            'is_approved' => true,
            'joined_at' => now(),
        ]);

        // Increment the members count
        $group->increment('members_count');

        // Add points for joining a group
        UserPoint::addPoints(
            $user->id,
            'group_joined',
            5,
            "Entrou no grupo: {$group->name}",
            $group->id,
            Group::class
        );

        return redirect()->route('grupos.show', $group->slug)
                         ->with('success', 'Você entrou no grupo com sucesso!');
    }

    /**
     * Leave a group.
     */
    public function leave(Group $group)
    {
        $user = Auth::user();

        // Check if the user is a member
        if (!$user->isMemberOf($group)) {
            return redirect()->route('grupos.show', $group->slug)
                             ->with('error', 'Você não é membro deste grupo.');
        }

        // Check if the user is the creator
        if ($group->creator_id === $user->id) {
            return redirect()->route('grupos.show', $group->slug)
                             ->with('error', 'Você é o criador deste grupo e não pode sair. Transfira a propriedade para outro membro ou exclua o grupo.');
        }

        // Remove the user from the group
        $user->groups()->detach($group->id);

        // Decrement the members count
        $group->decrement('members_count');

        return redirect()->route('grupos.index')
                         ->with('success', 'Você saiu do grupo com sucesso!');
    }
}
