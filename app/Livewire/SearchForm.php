<?php

namespace App\Livewire;

use App\Models\State;
use App\Models\City;
use App\Models\User;
use App\Models\Procura;
use App\Models\Hobby;
use Livewire\Component;

class SearchForm extends Component
{
    public $states = [];
    public $cities = [];
    public $selectedState = null;
    public $selectedCity = null;
    public $real_profiles = true;

    public $filters = [
        'id' => null,
        'username' => null,
        'anuncio' => null,
        'foto' => '1',
        'busco' => [],
        'ordenar' => 'last_access',
        'cadastrados' => '7_dias',
        'sexo' => [],
        'que_buscam' => [],
    ];

    public $procuras = [];
    public $results = [];
    public $hasSearched = false;

    public function rules()
    {
        return [
            'filters.id' => ['nullable'],
            'filters.username' => ['nullable'],
            'filters.anuncio' => ['nullable'],
            'selectedState' => ['nullable'],
            'selectedCity' => ['nullable'],
            'filters.sexo' => ['nullable', 'array'],
            'filters.busco' => ['nullable', 'array'],
            'filters.que_buscam' => ['nullable', 'array'],
        ];
    }

    public function mount()
    {
        $this->states = State::orderBy('name', 'asc')->get();
        $this->procuras = Procura::orderBy('nome', 'asc')->get();
        $this->hasSearched = false;
    }

    public function updatedSelectedState($stateId)
    {
        $this->cities = City::where('state_id', $stateId)->orderBy('name', 'asc')->get();
        $this->selectedCity = null;
    }

    public function search()
    {
        $this->resetErrorBag();
        $this->validate();

        // Verifica se pelo menos um filtro foi preenchido
        if (
            empty($this->filters['id']) &&
            empty($this->filters['username']) &&
            empty($this->filters['anuncio']) &&
            empty($this->selectedState) &&
            empty($this->selectedCity) &&
            empty($this->filters['foto']) &&
            empty($this->filters['busco']) &&
            empty($this->filters['ordenar']) &&
            empty($this->filters['cadastrados']) &&
            empty($this->filters['sexo']) &&
            empty($this->filters['que_buscam'])
        ) {
            $this->addError('global', 'Preencha pelo menos um campo para buscar.');
            $this->hasSearched = false;
            $this->results = [];
            return;
        }

        $this->hasSearched = true;
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

        if ($this->selectedState) {
            $query->where('state_id', $this->selectedState);
        }

        if ($this->selectedCity) {
            $query->where('city_id', $this->selectedCity);
        }

        // Filtro por sexo
        if (!empty($this->filters['sexo'])) {
            $query->whereIn('sexo', $this->filters['sexo']);
        }

        // Filtro por foto (verifica se o usuário tem fotos)
        if ($this->filters['foto'] === '1') {
            $query->whereHas('userPhotos');
        } elseif ($this->filters['foto'] === '0') {
            $query->whereDoesntHave('userPhotos');
        }

        // Filtro "Busco por" usando a tabela procuras
        if (!empty($this->filters['busco'])) {
            $query->whereHas('procuras', function ($q) {
                $q->whereIn('nome', $this->filters['busco']);
            });
        }

        // Filtro "Que Buscam por" usando a tabela procuras
        if (!empty($this->filters['que_buscam'])) {
            $query->whereHas('procuras', function ($q) {
                $q->whereIn('nome', $this->filters['que_buscam']);
            });
        }

        // Filtro por perfis reais (usuários com foto, bio e pelo menos um post)
        if ($this->real_profiles) {
            $query->whereHas('userPhotos')
                  ->whereNotNull('bio')
                  ->whereHas('posts');
        }

        // Ordenação
        if ($this->filters['ordenar']) {
            switch ($this->filters['ordenar']) {
                case 'id_crescente':
                    $query->orderBy('id', 'asc');
                    break;
                case 'id_decrescente':
                    $query->orderBy('id', 'desc');
                    break;
                case 'last_access':
                    $query->orderBy('last_seen', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        }

        // Filtro por data de cadastro
        if ($this->filters['cadastrados']) {
            $days = match ($this->filters['cadastrados']) {
                '7_dias' => 7,
                '15_dias' => 15,
                '30_dias' => 30,
                default => null,
            };

            if ($days && $days !== 'all') {
                $query->where('created_at', '>=', now()->subDays($days));
            }
        }

        // Ensure we only get users with a username
        $query->whereNotNull('username');

        // Eager load relationships needed for the user cards
        $this->results = $query->with([
            'userPhotos',
            'userCoverPhotos',
            'posts',
            'followers',
            'following',
            'city',
            'state',
            'procuras'
        ])->get();
    }

    public function render()
    {
        return view('livewire.search-form', [
            'states' => $this->states,
            'cities' => $this->cities,
            'procuras' => $this->procuras,
        ]);
    }
}
