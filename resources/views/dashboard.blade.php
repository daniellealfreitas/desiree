@php
    use Illuminate\Support\Facades\Auth;
@endphp

<x-layouts.app :title="__('Dashboard')">
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col md:flex-row gap-4 md:gap-6">
            <!-- Container para Feed de Postagens (aparece primeiro em mobile, segundo em desktop) -->
            <div class="w-full md:w-2/3 space-y-4 md:space-y-6 order-first md:order-last">
                <livewire:create-post />
                <livewire:postfeed />
            </div>

            <!-- Container para coluna esquerda (aparece segundo em mobile, primeiro em desktop) -->
            <div class="w-full md:w-1/3 space-y-4 md:space-y-6 order-last md:order-first">
                <!-- Perfil -->
                <livewire:user-profile :user="Auth::user()" />
                <!-- Ranking -->
                <livewire:leaderboard />
                <!-- Últimos Acessos e Perfis Sugeridos -->
                <div class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
                    <livewire:recent-users />
                    <livewire:recent-images />
                    <livewire:recent-videos />
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
