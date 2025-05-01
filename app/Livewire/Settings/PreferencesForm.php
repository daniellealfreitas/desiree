<?php

namespace App\Livewire\Settings;

use App\Models\Hobby;
use App\Models\Procura;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PreferencesForm extends Component
{
    public $selectedHobbies = [];
    public $selectedProcuras = [];
    
    public function mount()
    {
        $user = Auth::user();
        $this->selectedHobbies = $user->hobbies()->pluck('hobbies.id')->toArray();
        $this->selectedProcuras = $user->procuras()->pluck('procuras.id')->toArray();
    }

    public function updatePreferences()
    {
        $user = Auth::user();
        
        $user->hobbies()->sync($this->selectedHobbies);
        $user->procuras()->sync($this->selectedProcuras);

        $this->dispatch('preferences-updated');
    }

    public function render()
    {
        return view('livewire.settings.preferences-form', [
            'hobbies' => Hobby::all(),
            'procuras' => Procura::all(),
        ]);
    }
}