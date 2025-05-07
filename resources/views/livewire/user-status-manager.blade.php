<div
    wire:poll.15s="refreshStatus"
    class="mt-2 flex flex-col"
    x-data="{
        inactivityTimer: null,
        setupInactivityDetection() {
            if ({{ $user->id }} == {{ auth()->id() ?? 0 }}) {
                const events = ['mousemove', 'keydown', 'click', 'touchstart', 'scroll'];
                let lastActivity = Date.now();

                const checkInactivity = () => {
                    const inactiveTime = Date.now() - lastActivity;
                    if (inactiveTime > 180000 && '{{ $userStatus }}' !== 'away') { // 3 minutos
                        @this.$set('userStatus', 'away');
                        @this.call('updateStatus');
                    }
                };

                events.forEach(event => {
                    document.addEventListener(event, () => {
                        lastActivity = Date.now();
                        if ('{{ $userStatus }}' === 'away') {
                            @this.$set('userStatus', 'online');
                            @this.call('updateStatus');
                        }
                    });
                });

                this.inactivityTimer = setInterval(checkInactivity, 30000); // Verificar a cada 30 segundos
            }
        }
    }"
    x-init="setupInactivityDetection()"
>
    {{-- Status Visual --}}
    <div class="flex items-center gap-2">
        @switch($effectiveStatus)
            @case('online')
                <span class="flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2"></span>
                    <span class="text-green-400 font-semibold">Online</span>
                </span>
                @break
            @case('away')
                <span class="flex items-center">
                    <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                    <span class="text-yellow-400 font-semibold">Ausente</span>
                </span>
                @break
            @case('dnd')
                <span class="flex items-center">
                    <span class="w-3 h-3 bg-red-600 rounded-full mr-2"></span>
                    <span class="text-red-500 font-semibold">Não Perturbe</span>
                </span>
                @break
            @default
                <span class="flex items-center">
                    <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                    <span class="text-gray-400 font-semibold">Offline</span>
                </span>
                @if($user->last_seen)
                    <span class="small block text-xs text-slate-200 opacity-80 ml-2">
                        Visto {{ $user->last_seen->diffForHumans() }}
                    </span>
                @endif
        @endswitch

        {{-- Botão de edição (apenas para o próprio usuário) --}}
        @if($user->id === Auth::id())
            <button
                wire:click="toggleEditMode"
                class="ml-2 text-xs text-blue-400 hover:text-blue-300 focus:outline-none"
                title="Alterar status"
            >
                <x-flux::icon name="pencil" class="w-3 h-3" />
            </button>
        @endif
    </div>

    {{-- Seletor de status (apenas para o próprio usuário) --}}
    @if($isEditing && $user->id === Auth::id())
        <div class="mt-2 flex items-center">
            <select
                wire:model.defer="userStatus"
                wire:change="updateStatus"
                class="rounded border-gray-300 px-2 py-1 text-xs dark:bg-gray-700 dark:text-white"
            >
                @foreach($statusOptions as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>

            <button
                wire:click="updateStatus"
                class="ml-2 px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded"
            >
                Salvar
            </button>

            <button
                wire:click="toggleEditMode"
                class="ml-2 px-2 py-1 bg-gray-500 hover:bg-gray-600 text-white text-xs rounded"
            >
                Cancelar
            </button>
        </div>
    @endif
</div>
