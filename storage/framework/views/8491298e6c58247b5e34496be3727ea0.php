<?php

use App\Models\User;
use App\Models\FollowRequest;
use Illuminate\Support\Facades\Auth;

?>

<div id="ultimos_acessos">
    <h3 class="text-white bg-gray-800 p-3 rounded-t-lg font-semibold">Últimos Acessos</h3>
    <ul class="p-3 space-y-2">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="flex items-center justify-between space-x-3">
                <div class="flex items-center space-x-3">
                    <img src="<?php echo e(asset($user['user_photos'][0]['photo_path'] ?? 'images/default-avatar.jpg')); ?>" class="w-10 h-10 rounded-full object-cover">
                    <span>
                        <a href="/<?php echo e($user['username']); ?>" class="text-blue-500 hover:underline">
                            <?php echo e($user['name']); ?>

                        </a>
                    </span>
                </div>
                <!--[if BLOCK]><![endif]--><?php if($user['id'] !== Auth::id()): ?>
                    <button wire:click="toggleFollow(<?php echo e($user['id']); ?>)" 
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'px-4 py-2 rounded text-sm font-medium',
                                'bg-yellow-500 text-white' => $requestStatus[$user['id']] === 'pending',
                                'bg-gray-200 text-gray-800 cursor-not-allowed' => $requestStatus[$user['id']] === 'accepted',
                                'bg-blue-500 text-white hover:bg-blue-600' => !$requestStatus[$user['id']]
                            ]); ?>">
                        <!--[if BLOCK]><![endif]--><?php if($requestStatus[$user['id']] === 'pending'): ?>
                            <?php echo e(__('Solicitado')); ?>

                        <?php elseif($requestStatus[$user['id']] === 'accepted'): ?>
                            <?php echo e(__('Seguindo')); ?>

                        <?php else: ?>
                            <?php echo e(__('Seguir')); ?>

                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </ul>
</div><?php /**PATH C:\xampp\htdocs\desiree2\resources\views\livewire/recent-users.blade.php ENDPATH**/ ?>