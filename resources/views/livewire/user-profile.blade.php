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

    public function mount(): void
    {
        $this->user = Auth::user();
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

    public function avatar(): ?string
    {
        $path = UserPhoto::where('user_id', $this->user->id)->latest()->value('photo_path');
        return $path ? Storage::url($path) : null;
    }

    public function cover(): ?string
    {
        $coverPhoto = UserCoverPhoto::where('user_id', $this->user->id)->latest()->first();
        if (!$coverPhoto) {
            return null;
        }

        // Usar a versão recortada se disponível, caso contrário usar a original
        $path = $coverPhoto->cropped_photo_path ?? $coverPhoto->photo_path;
        return $path ? Storage::url($path) : null;
    }
}; ?>

<div id="Container" class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
    <div id="capa" class="relative h-32 bg-cover bg-center rounded-t-lg"
        style="background:url({{ $this->cover() ?? asset('images/users/capa.jpg') }}); background-size: cover; background-position: center;">
    </div>
    <div id="container_user"  class="relative z-10 -mt-12 flex flex-col items-center">
        <div id="avatar"   class="relative">
            <img src="{{ $this->avatar() ?? asset('images/users/avatar.jpg') }}"
            alt="Foto de Perfil" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
            <livewire:user-status-indicator :userId="$user->id" />
        </div>
        <h2 class="text-title text-xl font-semibold mt-2">{{ $user->name }}</h2>
        <p class="text-body-light">
            <a href="{{ route('user.profile', ['username' => $user->username]) }}" class="text-link hover:underline">
                {{ '@' . $user->username }}
            </a>
        </p>
        <div id="info_user" class="mt-4 flex justify-around w-full">
            <div class="text-center">
                <p class="text-subtitle text-lg font-semibold">{{ $this->postsCount() }}</p>
                <p class="text-body-lighter">Posts</p>
            </div>
            <div class="text-center">
                <p class="text-subtitle text-lg font-semibold">{{ $this->followingCount() }}</p>
                <p class="text-body-lighter">Seguindo</p>
            </div>
            <div class="text-center">
                <p class="text-subtitle text-lg font-semibold">{{ $this->followersCount() }}</p>
                <p class="text-body-lighter">Seguidores</p>
            </div>
        </div>
    </div>
</div>
