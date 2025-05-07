<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\CreatePost;
use App\Livewire\ProfileComponent;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Livewire::component('create-post', CreatePost::class);
        Livewire::component('profile-component', ProfileComponent::class);
        Livewire::component('settings.profile-form', \App\Livewire\Settings\ProfileForm::class);

        // Componentes de grupos
        Livewire::component('groups.create-group', \App\Livewire\Groups\CreateGroup::class);
        Livewire::component('groups.group-detail', \App\Livewire\Groups\GroupDetail::class);
        Livewire::component('groups.group-members', \App\Livewire\Groups\GroupMembers::class);
        Livewire::component('groups.group-posts', \App\Livewire\Groups\GroupPosts::class);
        Livewire::component('groups.group-invitations', \App\Livewire\Groups\GroupInvitations::class);
        Livewire::component('groups.group-list', \App\Livewire\Groups\GroupList::class);
    }
}
