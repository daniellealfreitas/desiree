<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\CreatePost;
use App\Livewire\ProfileComponent;
use App\Livewire\UserImages;
use App\Livewire\UserVideos;
use App\Livewire\UserFollowing;
use App\Livewire\UserFollowers;
use App\Livewire\UserPosts;
use App\Livewire\SendCharm;
use App\Livewire\Leaderboard;
use App\Livewire\SearchModal;
use App\Livewire\ToastNotification;
use App\Livewire\MessageNotifier;

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

        // Componentes de perfil
        Livewire::component('user-images', UserImages::class);
        Livewire::component('user-videos', UserVideos::class);
        Livewire::component('user-following', UserFollowing::class);
        Livewire::component('user-followers', UserFollowers::class);
        Livewire::component('user-posts', UserPosts::class);
        Livewire::component('send-charm', SendCharm::class);
        Livewire::component('leaderboard', Leaderboard::class);

        // Componentes de grupos
        Livewire::component('groups.create-group', \App\Livewire\Groups\CreateGroup::class);
        Livewire::component('groups.group-detail', \App\Livewire\Groups\GroupDetail::class);
        Livewire::component('groups.group-members', \App\Livewire\Groups\GroupMembers::class);
        Livewire::component('groups.group-posts', \App\Livewire\Groups\GroupPosts::class);
        Livewire::component('groups.group-invitations', \App\Livewire\Groups\GroupInvitations::class);
        Livewire::component('groups.group-list', \App\Livewire\Groups\GroupList::class);

        // Componentes de loja
        Livewire::component('shop.mini-cart', \App\Livewire\Shop\MiniCart::class);

        // Componente de busca
        Livewire::component('search-modal', SearchModal::class);

        // Componente de mensagens
        Livewire::component('messages', \App\Livewire\Messages::class);

        // Componente de notificações toast
        Livewire::component('toast-notification', ToastNotification::class);

        // Componente de notificação de mensagens
        Livewire::component('message-notifier', MessageNotifier::class);
    }
}
