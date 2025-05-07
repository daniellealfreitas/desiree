<x-layouts.app :title="__('Membros do Grupo')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Membros do Grupo: {{ $group->name }}</h1>

                <flux:button href="{{ route('grupos.show', $group->slug) }}" color="secondary">
                    <x-flux::icon icon="arrow-left" class="w-5 h-5 mr-2" />
                    Voltar ao Grupo
                </flux:button>
            </div>
        </div>

        <livewire:groups.group-members :group="$group" />
    </div>
</x-layouts.app>
