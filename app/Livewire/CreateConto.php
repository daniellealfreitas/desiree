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
    public $contoId;

    public function mount($contoId = null)
    {
        $this->contoId = $contoId;

        if ($this->contoId) {
            $conto = Conto::findOrFail($this->contoId);

            if (auth()->id() !== $conto->user_id) {
                abort(403, 'Você não tem permissão para editar este conto.');
            }

            $this->title = $conto->title;
            $this->category_id = $conto->category_id;
            $this->content = $conto->content;
        }
    }

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
                session()->flash('message', 'Conto publicado com sucesso!');
                return redirect()->to('/contos'); // Redireciona para a mesma página
            }
        } catch (\Exception $e) {
            Log::error('Error creating conto: ' . $e->getMessage());
            session()->flash('error', 'Erro ao criar o conto. Por favor, tente novamente!');
        }
    }

    public function update()
    {
        try {
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

            Log::info('Conto updated successfully', ['conto' => $conto->toArray()]);
            session()->flash('message', 'Conto atualizado com sucesso!');
            return redirect()->to('/contos');
        } catch (\Exception $e) {
            Log::error('Error updating conto: ' . $e->getMessage());
            session()->flash('error', 'Erro ao atualizar o conto. Por favor, tente novamente!');
        }
    }

    public function destroy()
    {
        try {
            $conto = Conto::findOrFail($this->contoId);

            if (auth()->id() !== $conto->user_id) {
                abort(403, 'Você não tem permissão para excluir este conto.');
            }

            $conto->delete();

            Log::info('Conto deleted successfully', ['conto_id' => $this->contoId]);
            session()->flash('message', 'Conto excluído com sucesso!');
            return redirect()->to('/contos');
        } catch (\Exception $e) {
            Log::error('Error deleting conto: ' . $e->getMessage());
            session()->flash('error', 'Erro ao excluir o conto. Por favor, tente novamente!');
        }
    }

    public function render()
    {
        // Carrega a view 'edit-conto' se $contoId estiver definido, caso contrário, 'create-conto'
        return view($this->contoId ? 'livewire.edit-conto' : 'livewire.create-conto', [
            'categories' => ContosCategoria::all()
        ]);
    }
}
