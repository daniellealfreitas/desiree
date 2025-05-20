<div wire:poll.300s class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Estatísticas de Tempo Online</h3>

    <div class="flex space-x-2 mb-4">
        <button
            wire:click="setPeriod('day')"
            class="px-3 py-1 text-sm rounded-md {{ $period === 'day' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}"
        >
            Hoje
        </button>
        <button
            wire:click="setPeriod('week')"
            class="px-3 py-1 text-sm rounded-md {{ $period === 'week' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}"
        >
            Esta Semana
        </button>
        <button
            wire:click="setPeriod('month')"
            class="px-3 py-1 text-sm rounded-md {{ $period === 'month' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}"
        >
            Este Mês
        </button>
    </div>

    <div class="space-y-4">
        @if($period === 'day')
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg text-center">
                    <div class="text-green-600 dark:text-green-400 text-sm font-medium">Online</div>
                    <div class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $this->formatMinutes($stats->minutes_online) }}</div>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-lg text-center">
                    <div class="text-yellow-600 dark:text-yellow-400 text-sm font-medium">Ausente</div>
                    <div class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $this->formatMinutes($stats->minutes_away) }}</div>
                </div>
                <div class="bg-red-100 dark:bg-red-900 p-3 rounded-lg text-center">
                    <div class="text-red-600 dark:text-red-400 text-sm font-medium">Não Perturbe</div>
                    <div class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $this->formatMinutes($stats->minutes_dnd) }}</div>
                </div>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg text-center">
                <div class="text-blue-600 dark:text-blue-400 text-sm font-medium">Tempo Total Online</div>
                <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                    {{ $this->formatMinutes($stats->minutes_online + $stats->minutes_away + $stats->minutes_dnd) }}
                </div>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg text-center">
                    <div class="text-green-600 dark:text-green-400 text-sm font-medium">Online</div>
                    <div class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $this->formatMinutes($stats['minutes_online']) }}</div>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-lg text-center">
                    <div class="text-yellow-600 dark:text-yellow-400 text-sm font-medium">Ausente</div>
                    <div class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $this->formatMinutes($stats['minutes_away']) }}</div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-red-100 dark:bg-red-900 p-3 rounded-lg text-center">
                    <div class="text-red-600 dark:text-red-400 text-sm font-medium">Não Perturbe</div>
                    <div class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $this->formatMinutes($stats['minutes_dnd']) }}</div>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg text-center">
                    <div class="text-blue-600 dark:text-blue-400 text-sm font-medium">Tempo Total</div>
                    <div class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $this->formatMinutes($stats['total_minutes']) }}</div>
                </div>
            </div>
            <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-lg text-center">
                <div class="text-purple-600 dark:text-purple-400 text-sm font-medium">
                    Dias Ativos ({{ $period === 'week' ? 'Esta Semana' : 'Este Mês' }})
                </div>
                <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $stats['days_active'] }}</div>
            </div>
        @endif
    </div>
</div>
