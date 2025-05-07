<?php

use App\Models\Post;

?>

<div class="mt-5">
    <h3 class="text-white bg-zinc-700 p-3 mt-4 rounded-t-lg font-semibold">Últimas Imagens</h3>
    
    <div class="grid grid-cols-2 gap-2 p-3">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="cursor-pointer" wire:click="openModal('<?php echo e(Storage::url($post->image)); ?>')">
                <img src="<?php echo e(Storage::url($post->image)); ?>" 
                     class="w-full h-32 object-cover rounded-lg hover:opacity-75 transition"
                     alt="Post image">
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
        <div class="fixed inset-0 bg-zinc-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="relative">
                <button wire:click="closeModal" 
                        class="absolute -top-8 right-0 text-white hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <img src="<?php echo e($selectedImage); ?>" class="max-h-[80vh] max-w-[90vw] rounded-lg">
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH C:\xampp\htdocs\desiree2\resources\views\livewire/recent-images.blade.php ENDPATH**/ ?>