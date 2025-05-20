<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SearchModal extends Component
{
    public $isOpen = false;
    public $searchTerm = '';
    public $searchResults = [];
    public $pages = [];
    
    protected $listeners = ['openSearchModal' => 'open'];
    
    public function mount()
    {
        $this->pages = $this->getAvailablePages();
    }
    
    public function open()
    {
        $this->isOpen = true;
        $this->searchTerm = '';
        $this->searchResults = [];
    }
    
    public function close()
    {
        $this->isOpen = false;
        $this->searchTerm = '';
        $this->searchResults = [];
    }
    
    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) < 2) {
            $this->searchResults = [];
            return;
        }
        
        $this->search();
    }
    
    public function search()
    {
        if (empty($this->searchTerm) || strlen($this->searchTerm) < 2) {
            $this->searchResults = [];
            return;
        }
        
        // Buscar usuários
        $users = User::where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('username', 'like', '%' . $this->searchTerm . '%');
            })
            ->whereNotNull('username')
            ->with(['userPhotos'])
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'photo' => $user->userPhotos->first() 
                        ? asset($user->userPhotos->first()->photo_path) 
                        : asset('images/users/avatar.jpg'),
                    'url' => '/' . $user->username,
                ];
            });
            
        // Buscar páginas
        $searchTerm = strtolower($this->searchTerm);
        $matchedPages = collect($this->pages)
            ->filter(function ($page) use ($searchTerm) {
                return Str::contains(strtolower($page['name']), $searchTerm) || 
                       Str::contains(strtolower($page['description']), $searchTerm);
            })
            ->take(5)
            ->values()
            ->all();
            
        // Combinar resultados
        $this->searchResults = [
            'users' => $users,
            'pages' => $matchedPages
        ];
    }
    
    private function getAvailablePages()
    {
        return [
            [
                'name' => 'Principal',
                'description' => 'Página inicial do site',
                'icon' => 'home',
                'url' => route('dashboard')
            ],
            [
                'name' => 'Loja',
                'description' => 'Produtos e serviços disponíveis',
                'icon' => 'shopping-bag',
                'url' => route('shop.index')
            ],
            [
                'name' => 'Radar',
                'description' => 'Encontre pessoas próximas',
                'icon' => 'map-pin',
                'url' => route('radar')
            ],
            [
                'name' => 'Mensagens',
                'description' => 'Suas conversas privadas',
                'icon' => 'inbox',
                'url' => route('caixa_de_mensagens')
            ],
            [
                'name' => 'Carteira',
                'description' => 'Gerencie seu saldo',
                'icon' => 'wallet',
                'url' => route('wallet.index')
            ],
            [
                'name' => 'Eventos',
                'description' => 'Eventos disponíveis',
                'icon' => 'calendar-days',
                'url' => route('events.index')
            ],
            [
                'name' => 'Grupos',
                'description' => 'Comunidades e grupos',
                'icon' => 'user-group',
                'url' => route('grupos.index')
            ],
            [
                'name' => 'Contos',
                'description' => 'Histórias e contos',
                'icon' => 'book-open',
                'url' => route('contos')
            ],
            [
                'name' => 'Busca Avançada',
                'description' => 'Busca detalhada de usuários',
                'icon' => 'magnifying-glass-circle',
                'url' => route('busca')
            ],
            [
                'name' => 'Configurações',
                'description' => 'Ajustes de perfil e conta',
                'icon' => 'cog-6-tooth',
                'url' => route('settings.profile')
            ],
        ];
    }
    
    public function render()
    {
        return view('livewire.search-modal');
    }
}
