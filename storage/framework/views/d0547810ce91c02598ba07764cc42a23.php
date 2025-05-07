<div
    wire:poll.10s="refreshStatus"
    class="relative"
    x-data="{
        showTooltip: false,
        inactivityTimer: null,
        setupInactivityDetection() {
            if (<?php echo e($userId); ?> == <?php echo e(auth()->id() ?? 0); ?>) {
                const events = ['mousemove', 'keydown', 'click', 'touchstart', 'scroll'];
                let lastActivity = Date.now();

                const checkInactivity = () => {
                    const inactiveTime = Date.now() - lastActivity;
                    if (inactiveTime > 180000) { // 3 minutos
                        window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('setAwayStatus');
                    }
                };

                events.forEach(event => {
                    document.addEventListener(event, () => {
                        lastActivity = Date.now();
                        if ('<?php echo e($status); ?>' === 'away' || '<?php echo e($status); ?>' === 'dnd') {
                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('setOnlineStatus');
                        }
                    });
                });

                this.inactivityTimer = setInterval(checkInactivity, 30000); // Verificar a cada 30 segundos
            }
        }
    }"
    x-init="setupInactivityDetection()"
    @click="showTooltip = !showTooltip"
>
    <span
        class="absolute left-1 top-1 transform translate-x-1/4 -translate-y-1/4 w-3 h-3 rounded-full border-1 border-white shadow-md
            <?php if($status=='online'): ?> bg-green-500
            <?php elseif($status=='away'): ?> bg-yellow-400
            <?php elseif($status=='dnd'): ?> bg-red-600
            <?php else: ?> bg-gray-500
            <?php endif; ?>
            <?php if($status=='online'): ?> animate-pulse
            <?php endif; ?>"
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
                <?php if($status=='online'): ?> text-green-600 dark:text-green-400
                <?php elseif($status=='away'): ?> text-yellow-600 dark:text-yellow-400
                <?php elseif($status=='dnd'): ?> text-red-600 dark:text-red-400
                <?php else: ?> text-gray-600 dark:text-gray-400
                <?php endif; ?>">
                <!--[if BLOCK]><![endif]--><?php if($status=='online'): ?> Online
                <?php elseif($status=='away'): ?> Ausente
                <?php elseif($status=='dnd'): ?> Não Perturbe
                <?php else: ?> Offline
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </p>
            <!--[if BLOCK]><![endif]--><?php if($status != 'online'): ?>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Visto por último: <?php echo e($this->formattedLastSeen); ?>

                </p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/user-status-indicator.blade.php ENDPATH**/ ?>