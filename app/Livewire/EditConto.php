<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Conto;
use App\Models\ContosCategoria;

class EditConto extends Component
{
    public $title;
    public $category_id;
    public $content;
    public $contoId;

    public function mount(Conto $conto)
    {
        if (auth()->id() !== $conto->user_id) {
            abort(403, 'Você não tem permissão para editar este conto.');
        }

        $this->contoId = $conto->id;
        $this->title = $conto->title;
        $this->category_id = $conto->category_id;
        $this->content = $conto->content;
    }

    public function rules()
    {
        return [
            'title' => 'required|min:3',
            'category_id' => 'required',
            'content' => 'required|min:10',
        ];
    }

    public function update()
    {
        $this->validate();

        $conto = Conto::findOrFail($this->contoId);

        if (auth()->id() !== $conto->user_id) {
            abort(403, 'Você não tem permissão para editar este conto.');
        }

        $conto->update([
            'title' => $this->title,
            'category_id' => $this->category_id,
            'content' => $this->content,
        ]);

        session()->flash('message', 'Conto atualizado com sucesso!');
        return redirect()->route('contos');
    }

    public function render()
    {
        return view('livewire.edit-conto', [
            'categories' => ContosCategoria::all(),
        ]);
    }
}
