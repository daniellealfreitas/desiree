<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Post;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller {
    // Lista as postagens
    public function index() {
        $posts = Post::with('user')->latest()->get();
        return view('posts.index', compact('posts'));
    }

    // Exibe o formulário de criação
    public function create() {
        return view('posts.create');
    }

    // Armazena o post
    public function store(Request $request, Group $group) {
        $request->validate([
            'content' => 'required|string',
        ]);

        Post::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'group_id' => $group->id,
        ]);

        // Increment user points
        $userPoint = UserPoint::firstOrCreate(['user_id' => auth()->id()]);
        $userPoint->increment('points', 10);

        return redirect()->route('grupos.show', $group);
    }

    // Exibe um post específico
    public function show(Post $post) {
        return view('post.show', compact('post'));
    }

    // Exibe o formulário de edição
    public function edit(Post $post) {
        return view('posts.edit', compact('post'));
    }

    // Atualiza o post
    public function update(Request $request, Post $post) {
        $data = $request->validate([
            'content' => 'required|string',
            'image'   => 'nullable|image',
            'video'   => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg'
        ]);

        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }
        if($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('posts', 'public');
        }

        $post->update($data);
        return redirect()->route('posts.index')->with('success', 'Post atualizado.');
    }

    // Remove o post
    public function destroy(Post $post) {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post removido.');
    }
}
