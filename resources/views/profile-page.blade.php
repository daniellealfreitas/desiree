<?php
    use Illuminate\Support\Facades\Auth;
?>

<x-layouts.app :title="__('Perfil')">
    <div class="max-w-7xl mx-auto px-4">
        <livewire:profile-component :username="$username" />
        <livewire:user-images />
        <livewire:user-videos />
        <livewire:user-following />
        <livewire:user-followers />
        <livewire:user-posts />
        <livewire:send-charm />
    </div>
</x-layouts.app>
