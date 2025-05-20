<?php

namespace App\Livewire;

use App\Models\ProfileVisit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ProfileVisitors extends Component
{
    use WithPagination;

    public $userId;
    public $perPage = 10;
    public $searchTerm = '';
    public $filterDate = 'all';

    public function mount($userId = null)
    {
        $this->userId = $userId ?? Auth::id();
    }

    public function render()
    {
        // Usar cache para evitar consultas desnecessárias
        $cacheKey = "visitors_{$this->userId}_{$this->filterDate}_{$this->searchTerm}_{$this->getPage()}";
        $visitors = cache()->remember($cacheKey, now()->addMinutes(5), function () {
            return $this->getVisitors();
        });

        return view('livewire.profile-visitors', [
            'visitors' => $visitors,
        ]);
    }

    public function getVisitors()
    {
        $query = ProfileVisit::with('visitor')
            ->where('visited_id', $this->userId)
            ->orderBy('visited_at', 'desc');

        // Aplicar filtro de data
        if ($this->filterDate === 'today') {
            $query->whereDate('visited_at', today());
        } elseif ($this->filterDate === 'week') {
            $query->whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->filterDate === 'month') {
            $query->whereMonth('visited_at', now()->month)
                  ->whereYear('visited_at', now()->year);
        }

        // Aplicar filtro de busca
        if (!empty($this->searchTerm)) {
            $query->whereHas('visitor', function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('username', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Método chamado quando o termo de busca é atualizado
     */
    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    /**
     * Método chamado quando o filtro de data é atualizado
     */
    public function updatingFilterDate()
    {
        $this->resetPage();
    }

    public function getAvatarUrl($path)
    {
        return $path ? Storage::url($path) : asset('images/users/avatar.jpg');
    }

    /**
     * Obtém a página atual da paginação
     */
    protected function getPage()
    {
        return request()->query('page', 1);
    }
}
