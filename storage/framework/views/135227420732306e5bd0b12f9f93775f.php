<?php

use App\Models\Post;

?>

<div>
    <h3 class="text-white bg-gray-800 p-3 mt-4 rounded-t-lg font-semibold">Últimos Vídeos</h3>
    
    <div class="grid grid-cols-2 gap-2 p-3">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="cursor-pointer" wire:click="openModal('<?php echo e(Storage::url($post->video)); ?>')">
                <video 
                    src="<?php echo e(Storage::url($post->video)); ?>" 
                    class="w-full h-32 object-cover rounded-lg hover:opacity-75 transition"
                    alt="Post video"
                    controls
                    preload="metadata"
                >
                    Seu navegador não suporta o elemento de vídeo.
                </video>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="relative">
                <button wire:click="closeModal" 
                        class="absolute -top-8 right-0 text-white hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <video src="<?php echo e($selectedVideo); ?>" controls class="max-h-[80vh] max-w-[90vw] rounded-lg" preload="metadata">
                    Seu navegador não suporta o elemento de vídeo.
                </video>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH C:\xampp\htdocs\desiree2\resources\views\livewire/recent-videos.blade.php ENDPATH**/ ?>