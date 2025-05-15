<div
    wire:poll.300s="refreshStatus"
    class="relative"
    x-data="{
        showTooltip: false,
        inactivityTimer: null,
        setupInactivityDetection() {
            if ({{ $userId }} == {{ auth()->id() ?? 0 }}) {
                const events = ['mousemove', 'keydown', 'click', 'touchstart', 'scroll'];
                let lastActivity = Date.now();

                const checkInactivity = () => {
                    const inactiveTime = Date.now() - lastActivity;
                    if (inactiveTime > 180000) { // 3 minutos
                        $wire.setAwayStatus();
                    }
                };

                events.forEach(event => {
                    document.addEventListener(event, () => {
                        lastActivity = Date.now();
                        if ('{{ $status }}' === 'away' || '{{ $status }}' === 'dnd') {
                            $wire.setOnlineStatus();
                        }
                    });
                });

                // Usar setInterval diretamente
                this.inactivityTimer = setInterval(checkInactivity, 120000);
            }
        }
    }"
    x-init="setupInactivityDetection()"
    @click="showTooltip=!showTooltip"
>
    <span
        class="absolute right-0 top-0 w-3 h-3 rounded-full border border-white shadow-md
            @if($status=='online') bg-green-500
            @elseif($status=='away') bg-yellow-400
            @elseif($status=='dnd') bg-red-600
            @else bg-gray-500
            @endif
            @if($status=='online') animate-pulse
            @endif"
        aria-label="status do usuário"
    ></span>

    <!-- Tooltip detalhado -->
    <div
        x-show="showTooltip"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.away="showTooltip = false"
        class="absolute z-50 mt-2 -ml-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5"
        style="top: 100%; left: 0;"
    >
        <div class="py-2 px-3">
            <p class="text-sm font-medium
                @if($status=='online') text-green-600 dark:text-green-400
                @elseif($status=='away') text-yellow-600 dark:text-yellow-400
                @elseif($status=='dnd') text-red-600 dark:text-red-400
                @else text-gray-600 dark:text-gray-400
                @endif">
                @if($status=='online') Online
                @elseif($status=='away') Ausente
                @elseif($status=='dnd') Não Perturbe
                @else Offline
                @endif
            </p>
            @if($status != 'online')
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Visto por último: {{ $this->formattedLastSeen }}
                </p>
            @endif
        </div>
    </div>
</div>