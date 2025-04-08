<div class="p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg">
    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php if(session()->has('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <form wire:submit.prevent="store" enctype="multipart/form-data">
        <textarea wire:model.defer="content" rows="3" 
            class="w-full p-3 border border-gray-300 rounded-lg" 
            placeholder="Compartilhe o que você pensa com fotos ou vídeos..."></textarea>
        
        <!--[if BLOCK]><![endif]--><?php if($image): ?>
            <div class="mt-2">
                <img src="<?php echo e($image->temporaryUrl()); ?>" 
                     class="max-w-xs h-auto rounded-lg shadow-sm" 
                     alt="Preview">
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <div class="flex justify-between mt-3">
            <div class="flex space-x-4">
                <label for="image" class="cursor-pointer flex items-center text-gray-500">
                    📷 <input wire:model="image" id="image" type="file" accept="image/*" class="hidden">
                </label>
                <!--[if BLOCK]><![endif]--><?php if($image): ?>
                    <span class="text-sm text-gray-500"><?php echo e($image->getClientOriginalName()); ?></span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <label for="video" class="cursor-pointer flex items-center text-gray-500">
                    🎥 <input wire:model="video" id="video" type="file" accept="video/*" class="hidden">
                </label>
                <!--[if BLOCK]><![endif]--><?php if($video): ?>
                    <span class="text-sm text-gray-500"><?php echo e($video->getClientOriginalName()); ?></span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg disabled:opacity-50" 
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50">
                <span wire:loading.remove>Postar</span>
                <span wire:loading>Enviando...</span>
            </button>
        </div>

        <div wire:loading wire:target="image,video" class="mt-2">
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div class="bg-red-600 h-2.5 rounded-full" style="width: 100%"></div>
            </div>
            <div class="text-sm text-gray-500 mt-1">Carregando arquivo...</div>
        </div>
    </form>
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/create-post.blade.php ENDPATH**/ ?>