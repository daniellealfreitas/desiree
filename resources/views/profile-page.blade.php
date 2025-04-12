<?php
    use Illuminate\Support\Facades\Auth;
?>

<x-layouts.app :title="__('Perfil')">
    <div class="max-w-7xl mx-auto px-4">
        <livewire:profile-component :username="$username" />
    </div>
</x-layouts.app>
