<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\UserPhoto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\State;
use App\Models\City;

class UserController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        // Validação da foto enviada
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user(); // Obtém o usuário autenticado

        // Salva a nova foto no diretório 'storage/app/public/photos'
        $path = $request->file('photo')->store('photos', 'public');

        // Cria um registro na tabela 'user_photos'
        UserPhoto::create([
            'user_id' => $user->id,
            'photo_path' => $path,
        ]);

        // Retorna para a página anterior com uma mensagem de sucesso
        return back()->with('success', 'Photo uploaded successfully!');
    }

    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return view('perfil', compact('user'));
    }

    public function getStates()
    {
        return view('states.index', ['states' => State::orderBy('name', 'asc')->get()]);
    }

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->orderBy('name', 'asc')->get();
        return view('cities.index', ['cities' => $cities]);
    }

    public function index()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('users.index', compact('users'));
    }
}