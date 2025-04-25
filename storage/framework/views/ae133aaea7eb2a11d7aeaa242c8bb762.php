<?php

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

?>

<div>
    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="p-6 mb-6 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-md">
            <div class="flex items-center space-x-3 mb-4">
                <img src="<?php echo e(!empty($post->user->userPhotos->first()) ? Storage::url($post->user->userPhotos->first()->photo_path) : asset('images/users/default.jpg')); ?>" 
                     class="w-10 h-10 rounded-full object-cover">
                <div>
                    <h4 class="font-semibold"><?php echo e($post->user->name); ?></h4>
                    <p class="text-sm text-gray-500">
                        <a href="/<?php echo e($post->user->username); ?>" class="hover:underline"> <?php echo e('@'.$post->user->username); ?></a>
                    </p>
                </div>
            </div>

            <!--[if BLOCK]><![endif]--><?php if($post->image): ?>
                <img src="<?php echo e(asset( $post->image)); ?>" class="w-full rounded-lg mb-4">
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <p class="text-gray-700"><?php echo e($post->body); ?></p>

            <div class="mt-3 flex items-center space-x-2">
                <button
                    wire:click="toggleLike(<?php echo e($post->id); ?>)"
                    class="<?php echo e($post->isLikedBy(auth()->user()) ? 'text-red-600' : 'text-gray-400'); ?>"
                >
                    ❤️ Curtir
                </button>
                <div class="relative group">
                    <span><?php echo e($post->likedByUsers->count()); ?> Curtidas</span>
                    
                    <!-- Tooltip com lista de usuários -->
                    <!--[if BLOCK]><![endif]--><?php if($post->likedByUsers->count() > 0): ?>
                        <div class="absolute bottom-full left-0 mb-2 hidden group-hover:block bg-black text-white p-2 rounded-lg shadow-lg z-50 w-48">
                            <div class="text-sm">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $post->likedByUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center space-x-2 mb-1">
                                        <img src="<?php echo e(!empty($user->userPhotos->first()) ? Storage::url($user->userPhotos->first()->photo_path) : asset('images/users/default.jpg')); ?>" 
                                             class="w-6 h-6 rounded-full object-cover">
                                        <span><?php echo e($user->name); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div class="absolute -bottom-1 left-4 w-3 h-3 bg-black transform rotate-45"></div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <div class="mt-4 space-y-4">
                <form wire:submit="addComment(<?php echo e($post->id); ?>)" class="flex gap-2">
                    <input
                        wire:model="newComment.<?php echo e($post->id); ?>"
                        type="text"
                        class="flex-1 p-2 border border-neutral-200 dark:border-neutral-700 rounded-lg"
                        placeholder="Escreva um comentário..."
                    >
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Comentar
                    </button>
                </form>

                <!-- Lista de comentários -->
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start space-x-3 p-3 bg-zinc-700 rounded-lg hover:bg-zinc-600 transition border-neutral-200 dark:border-neutral-700">
                        <img src="<?php echo e(!empty($comment->user->userPhotos->first()) ? Storage::url($comment->user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg')); ?>" 
                             class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <p class="font-semibold">
                                <a href="/<?php echo e($comment->user->username); ?>" class="hover:underline">
                                    <?php echo e($comment->user->username); ?>

                                </a>
                            </p>
                            <p class="text-gray-100"><?php echo e($comment->body); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

    <button wire:click="loadMore" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Load More
    </button>
</div><?php /**PATH C:\xampp\htdocs\desiree2\resources\views\livewire/postfeed.blade.php ENDPATH**/ ?>