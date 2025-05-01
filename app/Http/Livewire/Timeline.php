<?php
namespace App\Http\Livewire;

use Livewire\Component;

class Timeline extends Component
{
    public $rows = [];

    public function mount()
    {
        $this->rows = [
            [1, 2, 3, 4],
        ];
    }

    public function addDiv()
    {
        $lastRow = end($this->rows);
        if (count($lastRow) < 4) {
            $this->rows[key($this->rows)][] = count($this->rows, COUNT_RECURSIVE) + 1;
        } else {
            $this->rows[] = [count($this->rows, COUNT_RECURSIVE) + 1];
        }
    }

    public function render()
    {
        return view('livewire.timeline');
    }
}
