<?php
namespace App\Http\Controllers;

use App\Models\Conto;
use Illuminate\Http\Request;

class ContoController extends Controller
{
    public function index()
    {
        $contos = Conto::with('user')->latest()->get();
        return view('contos.index', compact('contos'));
    }

    public function show(Conto $conto)
    {
        return view('contos.show', compact('conto'));
    }

    public function destroy(Conto $conto)
    {
        $this->authorize('delete', $conto);
        $conto->delete();

        return redirect()->route('contos.index')->with('message', 'Conto deletado com sucesso!');
    }
}
