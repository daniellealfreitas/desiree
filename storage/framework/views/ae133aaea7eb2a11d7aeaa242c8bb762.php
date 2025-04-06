<?php

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

?>

<div>
    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="p-6 mb-6 border border-gray-200 rounded-lg shadow-md">
            <div class="flex items-center space-x-3 mb-4">
                <img src="<?php echo e(asset('images/users/' . ($post->user->avatar ?? 'default.jpg'))); ?>" class="w-10 h-10 rounded-full">
                <div>
                    <h4 class="font-semibold"><?php echo e($post->user->name); ?></h4>
                    <p class="text-sm text-gray-500">{{ $post->user->username }}</p>
                </div>
            </div>

            <!--[if BLOCK]><![endif]--><?php if($post->image): ?>
                <img src="<?php echo e(asset('images/posts/' . $post->image)); ?>" class="w-full rounded-lg mb-4">
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <p class="text-gray-700"><?php echo e($post->content); ?></p>

            <div class="mt-3 flex items-center space-x-2">
                <button
                    wire:click="toggleLike(<?php echo e($post->id); ?>)"
                    class="<?php echo e($post->isLikedBy(auth()->user()) ? 'text-red-600' : 'text-gray-400'); ?>"
                >
                    ❤️ Like
                </button>
                <span><?php echo e($post->likedByUsers->count()); ?> likes</span>
            </div>

            <input
                type="text"
                class="w-full p-2 border border-gray-300 rounded-lg mt-3"
                placeholder="Write a comment..."
            >
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

    <button wire:click="loadMore" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Load More
    </button>
</div><?php /**PATH C:\xampp\htdocs\desiree2\resources\views\livewire/postfeed.blade.php ENDPATH**/ ?>