<?php

namespace App\Http\Controllers;

use App\Models\UserLevel;
use Illuminate\Http\Request;

class UserLevelController extends Controller {
    // Exibe os níveis de usuário
    public function index() {
        $levels = UserLevel::all();
        return view('user_levels.index', compact('levels'));
    }
}
