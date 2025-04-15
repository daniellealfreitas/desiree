<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conto;
use App\Models\ContosCategoria;
use Livewire\WithPagination;

class ListContos extends Component
{
    use WithPagination;

    public $selectedCategory = '';

    public function render()
    {
        $query = Conto::with(['user', 'category'])
            ->latest();

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        return view('livewire.list-contos', [
            'contos' => $query->paginate(5),
            'categories' => ContosCategoria::all()
        ]);
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }
}