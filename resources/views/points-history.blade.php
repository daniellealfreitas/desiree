<x-layouts.app :title="__('Histórico de Pontuação')">
    <div class="container mx-auto py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Histórico de Pontuação</h1>
            <p class="text-gray-600 dark:text-gray-400">Acompanhe sua evolução e conquistas na plataforma</p>
        </div>
        
        @if(isset($userId))
            <livewire:user-points-history :userId="$userId" />
        @else
            <livewire:user-points-history />
        @endif
    </div>
</x-layouts.app>
