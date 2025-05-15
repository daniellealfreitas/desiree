<div wire:poll.600s="checkFriendsStatus">
    <!--[if BLOCK]><![endif]--><?php if(count($newOnlineFriends) > 0): ?>
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => { show = false }, 5000)"
            class="fixed bottom-4 right-4 z-50 max-w-sm"
        >
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $newOnlineFriends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $friend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 mb-2 flex items-center space-x-3 border-l-4 border-green-500 transform transition-transform duration-300 hover:scale-105">
                    <div class="relative flex-shrink-0">
                        <img src="<?php echo e($friend['avatar'] ? asset($friend['avatar']) : asset('images/default-avatar.jpg')); ?>" class="w-10 h-10 rounded-full object-cover">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($friend['name']); ?></p>
                        <p class="text-xs text-green-600 dark:text-green-400">Acabou de ficar online</p>
                    </div>
                    <button
                        @click="show = false"
                        class="ml-auto text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                    >
                        <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'x-mark','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x-mark','class' => 'w-4 h-4']); ?>
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
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/friend-status-notifier.blade.php ENDPATH**/ ?>