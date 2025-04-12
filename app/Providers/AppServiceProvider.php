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
    }
}
