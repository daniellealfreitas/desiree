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
            class="w-full p-3 border border-gray-500 rounded-lg text-gray-300"
            placeholder="Compartilhe o que você pensa com fotos ou vídeos..."></textarea>

        <!--[if BLOCK]><![endif]--><?php if($image): ?>
            <div class="mt-2">
                <img src="<?php echo e($image->temporaryUrl()); ?>"
                     class="max-w-xs h-auto rounded-lg shadow-sm"
                     alt="Preview">
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <!--[if BLOCK]><![endif]--><?php if($video): ?>
            <div class="mt-2">
                <video controls class="max-w-xs h-auto rounded-lg shadow-sm">
                    <source src="<?php echo e($video->temporaryUrl()); ?>" type="video/mp4">
                    Seu navegador não suporta o elemento de vídeo.
                </video>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <div class="flex justify-between mt-3">
            <div class="flex space-x-4">
                <label for="image" class="cursor-pointer flex items-center text-gray-500">
                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'photo','variant' => ''.e($image ? 'solid' : 'outline').'','class' => 'w-5 h-5 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'photo','variant' => ''.e($image ? 'solid' : 'outline').'','class' => 'w-5 h-5 mr-1']); ?>
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
                    <input wire:model="image" id="image" type="file" accept="image/*" class="hidden">
                </label>
                <!--[if BLOCK]><![endif]--><?php if($image): ?>
                    <span class="text-sm text-gray-500">
                        <!--[if BLOCK]><![endif]--><?php if(is_object($image) && method_exists($image, 'getClientOriginalName')): ?>
                            <?php echo e($image->getClientOriginalName()); ?>

                        <?php else: ?>
                            Imagem selecionada
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <label for="video" class="cursor-pointer flex items-center text-gray-500">
                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'video-camera','variant' => ''.e($video ? 'solid' : 'outline').'','class' => 'w-5 h-5 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'video-camera','variant' => ''.e($video ? 'solid' : 'outline').'','class' => 'w-5 h-5 mr-1']); ?>
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
                    <input wire:model="video" id="video" type="file" accept="video/*" class="hidden">
                </label>
                <!--[if BLOCK]><![endif]--><?php if($video): ?>
                    <span class="text-sm text-gray-500">
                        <!--[if BLOCK]><![endif]--><?php if(is_object($video) && method_exists($video, 'getClientOriginalName')): ?>
                            <?php echo e($video->getClientOriginalName()); ?>

                        <?php else: ?>
                            Vídeo selecionado
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg disabled:opacity-50"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50">
                <span wire:loading.remove>Postar</span>
                <span wire:loading>Enviando...</span>
            </button>
        </div>

        <div wire:loading wire:target="image,video" class="mt-2">
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div class="bg-purple-600  h-2.5 rounded-full" style="width: 100%"></div>
            </div>
            <div class="text-sm text-gray-500 mt-1">Carregando arquivo...</div>
        </div>
    </form>
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/create-post.blade.php ENDPATH**/ ?>