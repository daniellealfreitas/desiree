<div
    wire:poll.15s="refreshStatus"
    class="mt-2 flex flex-col"
    x-data="{
        inactivityTimer: null,
        setupInactivityDetection() {
            if (<?php echo e($user->id); ?> == <?php echo e(auth()->id() ?? 0); ?>) {
                const events = ['mousemove', 'keydown', 'click', 'touchstart', 'scroll'];
                let lastActivity = Date.now();

                const checkInactivity = () => {
                    const inactiveTime = Date.now() - lastActivity;
                    if (inactiveTime > 180000 && '<?php echo e($userStatus); ?>' !== 'away') { // 3 minutos
                        window.Livewire.find('<?php echo e($_instance->getId()); ?>').$set('userStatus', 'away');
                        window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('updateStatus');
                    }
                };

                events.forEach(event => {
                    document.addEventListener(event, () => {
                        lastActivity = Date.now();
                        if ('<?php echo e($userStatus); ?>' === 'away') {
                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').$set('userStatus', 'online');
                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('updateStatus');
                        }
                    });
                });

                this.inactivityTimer = setInterval(checkInactivity, 30000); // Verificar a cada 30 segundos
            }
        }
    }"
    x-init="setupInactivityDetection()"
>
    
    <div class="flex items-center gap-2">
        <!--[if BLOCK]><![endif]--><?php switch($effectiveStatus):
            case ('online'): ?>
                <span class="flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2"></span>
                    <span class="text-green-400 font-semibold">Online</span>
                </span>
                <?php break; ?>
            <?php case ('away'): ?>
                <span class="flex items-center">
                    <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                    <span class="text-yellow-400 font-semibold">Ausente</span>
                </span>
                <?php break; ?>
            <?php case ('dnd'): ?>
                <span class="flex items-center">
                    <span class="w-3 h-3 bg-red-600 rounded-full mr-2"></span>
                    <span class="text-red-500 font-semibold">Não Perturbe</span>
                </span>
                <?php break; ?>
            <?php default: ?>
                <span class="flex items-center">
                    <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                    <span class="text-gray-400 font-semibold">Offline</span>
                </span>
                <!--[if BLOCK]><![endif]--><?php if($user->last_seen): ?>
                    <span class="small block text-xs text-slate-200 opacity-80 ml-2">
                        Visto <?php echo e($user->last_seen->diffForHumans()); ?>

                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($user->id === Auth::id()): ?>
            <button
                wire:click="toggleEditMode"
                class="ml-2 text-xs text-blue-400 hover:text-blue-300 focus:outline-none"
                title="Alterar status"
            >
                <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'pencil','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'pencil','class' => 'w-3 h-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
            </button>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($isEditing && $user->id === Auth::id()): ?>
        <div class="mt-2 flex items-center">
            <select
                wire:model.defer="userStatus"
                wire:change="updateStatus"
                class="rounded border-gray-300 px-2 py-1 text-xs dark:bg-gray-700 dark:text-white"
            >
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
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
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/user-status-manager.blade.php ENDPATH**/ ?>