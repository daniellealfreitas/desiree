<div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Leaderboard</h2>
    <ul>
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                <span><?php echo e($user->name); ?></span>
                <span class="font-bold text-yellow-400"><?php echo e($user->ranking_points); ?></span>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </ul>
</div><?php /**PATH C:\xampp\htdocs\desiree2\resources\views\livewire/leaderboard.blade.php ENDPATH**/ ?>