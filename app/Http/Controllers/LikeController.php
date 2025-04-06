<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LikeController extends Controller {
    // Alterna a curtida de um post
    public function toggle($postId) {
        $userId = Auth::id();
        $like = Like::where('user_id', $userId)->where('post_id', $postId)->first();
        if($like) {
            $like->delete();
        } else {
            Like::create(['user_id' => $userId, 'post_id' => $postId]);
        }
        return back();
    }
}
