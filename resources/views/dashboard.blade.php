@php
    use Illuminate\Support\Facades\Auth;
@endphp

<x-layouts.app :title="__('Dashboard')">
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Container para coluna esquerda -->
        <div class="col-span-1 space-y-6">
            <!-- Perfil -->
            <livewire:user-profile :user="Auth::user()" />
            <!-- Últimos Acessos e Perfis Sugeridos -->
            <div class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
                <livewire:recent-users />
                <livewire:recent-images />
                <livewire:recent-videos />
            </div>
        </div>
        <!-- Container para Feed de Postagens -->
        <div class="col-span-2 space-y-6">
            <livewire:create-post />
            <livewire:postfeed />
        </div>
    </div>
</x-layouts.app>
