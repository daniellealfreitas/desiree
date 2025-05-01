<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Validation\Rule;

class ProfileForm extends Component
{
    public $name;
    public $email;
    public $username;
    public $sexo;
    public $aniversario;
    public $privado;
    public $bio;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->sexo = $user->sexo;
        $this->aniversario = $user->aniversario;
        $this->privado = $user->privado;
        $this->bio = $user->bio;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'sexo' => ['nullable', 'in:casal,homem,mulher'],
            'aniversario' => ['nullable', 'date'],
            'privado' => ['boolean'],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($validated);

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function render()
    {
        return view('livewire.settings.profile-form');
    }
}