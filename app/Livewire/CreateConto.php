<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conto;
use App\Models\ContosCategoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateConto extends Component
{
    public $title = '';
    public $category_id = '';
    public $content = '';
    
    public function rules()
    {
        return [
            'title' => 'required|min:3',
            'content' => 'required|min:10'
        ];
    }

    public function store()
    {
        try {
            $this->validate();

            $conto = Conto::create([
                'title' => $this->title,
                'category_id' => $this->category_id,
                'content' => $this->content,
                'user_id' => auth()->id(),
            ]);

            if ($conto) {
                Log::info('Conto created successfully', ['conto' => $conto->toArray()]);
                $this->reset(['title', 'category_id', 'content']);
                session()->flash('message', 'Conto publicado com sucesso!');
                // Não redireciona, apenas mostra a mensagem na tela
            }
        } catch (\Exception $e) {
            Log::error('Error creating conto: ' . $e->getMessage());
            session()->flash('error', 'Erro ao criar o conto. Por favor, tente novamente!');
        }
    }

    public function render()
    {
        return view('livewire.create-conto', [
            'categories' => ContosCategoria::all()
        ]);
    }
}
