<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SearchForm extends Component
{
    public $filters = [
        'id' => '',
        'username' => '',
        'anuncio' => '',
    ];

    public $selectedState = '';
    public $selectedCity = '';
    public $states = [];
    public $cities = [];
    public $results = [];
    public $hasSearched = false; // New property

    public function mount()
    {
        // Load states and cities logic here
        // $this->states = ...;
        // $this->cities = ...;
        // $this->results = [];
        $this->hasSearched = false;
    }

    public function search()
    {
        // Search logic here, replace with actual querying
        // $this->results = Model::where(...)->get();
        $this->hasSearched = true;
    }

    public function render()
    {
        return view('livewire.search-form');
    }
}