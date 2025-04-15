<?php

namespace App\Livewire;

use App\Models\State;
use App\Models\City;
use App\Models\User;
use Livewire\Component;

class SearchForm extends Component
{
    public $states = [];
    public $cities = [];
    public $selectedState = null;
    public $selectedCity = null;
    public $filters = [
        'id' => null,
        'username' => null,
        'anuncio' => null,
        'foto' => null,
        'busco' => [],
        'ordenar' => null,
        'cadastrados' => null,
    ];
    public $results = [];

    public function mount()
    {
        $this->states = State::orderBy('name', 'asc')->get();
    }

    public function updatedSelectedState($stateId)
    {
        $this->cities = City::where('state_id', $stateId)->orderBy('name', 'asc')->get();
        $this->selectedCity = null;
    }

    public function search()
    {
        $query = User::query();

        if ($this->filters['id']) {
            $query->where('id', $this->filters['id']);
        }

        if ($this->filters['username']) {
            $query->where('username', 'like', '%' . $this->filters['username'] . '%');
        }

        if ($this->filters['anuncio']) {
            $query->where('anuncio', 'like', '%' . $this->filters['anuncio'] . '%');
        }

        if ($this->selectedCity) {
            $query->where('city_id', $this->selectedCity);
        }

        if ($this->filters['foto']) {
            $query->whereNotNull('photo');
        }

        if (!empty($this->filters['busco'])) {
            $query->whereHas('lookingFor', function ($q) {
                $q->whereIn('option', $this->filters['busco']);
            });
        }

        if ($this->filters['ordenar']) {
            $query->orderBy('id', $this->filters['ordenar'] === 'id_crescente' ? 'asc' : 'desc');
        }

        if ($this->filters['cadastrados']) {
            $days = match ($this->filters['cadastrados']) {
                '7_dias' => 7,
                '15_dias' => 15,
                '30_dias' => 30,
                default => null,
            };

            if ($days) {
                $query->where('created_at', '>=', now()->subDays($days));
            }
        }

        $this->results = $query->get();
    }

    public function render()
    {
        return view('livewire.search-form', [
            'states' => $this->states,
            'cities' => $this->cities,
        ]);
    }
}
