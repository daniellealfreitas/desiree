<?php

use App\Models\User;
use App\Models\Post;
use App\Models\UserPhoto;
use App\Models\UserCoverPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;

new class extends Component {
    public User $user;
    public array $followStatus = [];
    
    public function mount(string $username): void 
    {
        $this->user = User::with(['followers', 'posts'])
            ->where('username', $username)
            ->firstOrFail();
            
        $this->followStatus[$this->user->id] = $this->user->followers->contains(Auth::id());
    }
    
    public function postsCount(): int 
    {
        return Post::where('user_id', $this->user->id)->count();
    }
    
    public function followingCount(): int 
    {
        return $this->user->following()->count();
    }
    
    public function followersCount(): int 
    {
        return $this->user->followers()->count();
    }
    
    public function imagesCount(): int 
    {
        return Post::where('user_id', $this->user->id)
            ->where('type', 'image')
            ->count();
    }
    
    public function avatar(): ?string 
    {
        $path = UserPhoto::where('user_id', $this->user->id)->latest()->value('photo_path');
        return $path ? Storage::url($path) : null;
    }
    
    public function cover(): ?string 
    {
        $path = UserCoverPhoto::where('user_id', $this->user->id)->latest()->value('photo_path');
        return $path ? Storage::url($path) : null;
    }
    
    public function toggleFollow(int $userId): void
    {
        $user = User::find($userId);
        if ($this->followStatus[$userId]) {
            $user->followers()->detach(Auth::id());
            $this->followStatus[$userId] = false;
        } else {
            $user->followers()->attach(Auth::id());
            $this->followStatus[$userId] = true;
        }
    }

    public function render() 
    {
        return view('perfil')
            ->layout('layouts.app');
    }
}; ?>

<div>
    <div
        x-data="{ show: false }"
        x-init="setTimeout(() => show = true, 200)"
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-show="show"
        class="relative w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden"
    >
        <div class="w-full h-64 bg-cover bg-center" style="background-image: url('{{ $this->cover() ?? asset('images/default-banner.jpg') }}');">
            <div class="absolute top-4 left-4">
                <div class="w-32 h-32 rounded-full border-4 border-white overflow-hidden shadow-md">
                    <img src="{{ $this->avatar() ?? asset('images/default-avatar.jpg') }}" class="w-full h-full object-cover" />
                </div>
            </div>
        </div>

        <div class="px-6 pt-6 pb-4">
            <div class="ml-36">
                <h2 class="text-2xl font-bold dark:text-white">{{ $user->name }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">@{{ $user->username }} <span class="text-xs text-gray-400">[{{ $user->id }}]</span></p>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between border-t border-gray-200 dark:border-gray-700 px-6 py-3 text-sm text-gray-700 dark:text-gray-300">
            <div class="flex flex-wrap gap-4">
                <div>Imagens ({{ $this->imagesCount() }})</div>
                <div>Seguindo ({{ $this->followingCount() }})</div>
                <div>Seguidores ({{ $this->followersCount() }})</div>
                <div>POSTS ({{ $this->postsCount() }})</div>
                <div>Amigos ({{ $user->friends_count ?? 0 }})</div>
            </div>

            <div class="flex gap-2">
                @if($user->id !== Auth::id())
                    <button wire:click="toggleFollow({{ $user->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition">
                        {{ $followStatus[$user->id] ? 'Deixar de Seguir' : 'Seguir' }}
                    </button>
                    <button wire:click="toggleAmizade" class="bg-red-700 hover:bg-red-800 text-white px-3 py-1 rounded text-xs transition">
                        {{ $amigo ? 'Remover Amigo' : 'Adicionar Amigo' }}
                    </button>
                    <button wire:click="abrirChat" class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1 rounded text-xs transition">
                        💬 Chat
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
