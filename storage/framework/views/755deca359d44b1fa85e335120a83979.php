<div>
    <div class="mb-4">
        <button wire:click="fetchNearbyUsers" class="btn btn-primary">Atualizar Localização</button>
    </div>

    <ul class="list-group">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><?php echo e($user->name ?? 'Usuário Desconhecido'); ?> (<?php echo e($user->username ?? 'N/A'); ?>)</span>
                <span>
                    <!--[if BLOCK]><![endif]--><?php if(isset($user->distance_m)): ?>
                        <?php echo e(number_format($user->distance_m, 0)); ?> metros
                    <?php else: ?>
                        <?php echo e(number_format($user->distance_km, 2)); ?> km
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </span>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="list-group-item text-warning">Nenhum usuário encontrado próximo.</li>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </ul>
</div><?php /**PATH C:\xampp\htdocs\desiree2\resources\views\livewire/nearby-users.blade.php ENDPATH**/ ?>