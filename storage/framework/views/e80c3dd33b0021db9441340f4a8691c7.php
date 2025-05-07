<div>
    <!-- Category Filter -->
    <div class="mb-6">
        <?php if (isset($component)) { $__componentOriginale5140a44d7461450cb1378cd5b47dfc8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale5140a44d7461450cb1378cd5b47dfc8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::radio.group.index','data' => ['wire:model.live' => 'selectedCategory','label' => 'Filtrar por categoria','variant' => 'segmented']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'selectedCategory','label' => 'Filtrar por categoria','variant' => 'segmented']); ?>
            <?php if (isset($component)) { $__componentOriginal63a6e9bef56b25b50cfa996fe1154357 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal63a6e9bef56b25b50cfa996fe1154357 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::radio.index','data' => ['value' => '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::radio'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => '']); ?>Todas as categorias <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal63a6e9bef56b25b50cfa996fe1154357)): ?>
<?php $attributes = $__attributesOriginal63a6e9bef56b25b50cfa996fe1154357; ?>
<?php unset($__attributesOriginal63a6e9bef56b25b50cfa996fe1154357); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal63a6e9bef56b25b50cfa996fe1154357)): ?>
<?php $component = $__componentOriginal63a6e9bef56b25b50cfa996fe1154357; ?>
<?php unset($__componentOriginal63a6e9bef56b25b50cfa996fe1154357); ?>
<?php endif; ?>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginal63a6e9bef56b25b50cfa996fe1154357 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal63a6e9bef56b25b50cfa996fe1154357 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::radio.index','data' => ['value' => ''.e($category->id).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::radio'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => ''.e($category->id).'']); ?><?php echo e($category->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal63a6e9bef56b25b50cfa996fe1154357)): ?>
<?php $attributes = $__attributesOriginal63a6e9bef56b25b50cfa996fe1154357; ?>
<?php unset($__attributesOriginal63a6e9bef56b25b50cfa996fe1154357); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal63a6e9bef56b25b50cfa996fe1154357)): ?>
<?php $component = $__componentOriginal63a6e9bef56b25b50cfa996fe1154357; ?>
<?php unset($__componentOriginal63a6e9bef56b25b50cfa996fe1154357); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale5140a44d7461450cb1378cd5b47dfc8)): ?>
<?php $attributes = $__attributesOriginale5140a44d7461450cb1378cd5b47dfc8; ?>
<?php unset($__attributesOriginale5140a44d7461450cb1378cd5b47dfc8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale5140a44d7461450cb1378cd5b47dfc8)): ?>
<?php $component = $__componentOriginale5140a44d7461450cb1378cd5b47dfc8; ?>
<?php unset($__componentOriginale5140a44d7461450cb1378cd5b47dfc8); ?>
<?php endif; ?>
    </div>



    <!-- Contos Grid -->
    <div class="md:columns-2 lg:columns-3 gap-6 p-4 sm:p-1">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $contos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="animate-in zoom-in duration-200">
                <div class="ring-1 rounded-lg flex flex-col space-y-2 p-4 break-inside-avoid mb-6 hover:ring-2 ring-gray-300 hover:ring-sky-400 transform duration-200 hover:shadow-sky-200 hover:shadow-md z-0 relative">
                    <div class="flex flex-col break-inside-avoid-page z-0 relative">
                        <div class="flex justify-between">
                            <div class="flex space-x-6">
                                <div class="flex space-x-4 flex-shrink-0 w-52">
                                    <a href="<?php echo e(route('user.profile', $conto->user->username)); ?>">
                                        <img src="<?php echo e($conto->user->userPhotos->first() ? asset($conto->user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg')); ?>"
                                             class="w-10 h-10 rounded-full">
                                    </a>
                                    <div>
                                        <a href="<?php echo e(route('user.profile', $conto->user->username)); ?>" class="font-semibold hover:underline">
                                            <?php echo e($conto->user->name); ?>

                                        </a>
                                        <div class="text-sm">
                                            <?php echo e('@' . $conto->user->username); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                <?php echo e($conto->category->name); ?>

                            </div>
                        </div>

                        <div class="break-inside-avoid-page mt-2">
                            <h3 class="text-lg font-semibold p-1">
                                <a href="<?php echo e(route('contos.show', $conto->id)); ?>" class="hover:text-sky-600 hover:underline">
                                    <?php echo e($conto->title); ?>

                                </a>
                            </h3>
                            <div class="prose dark:prose-invert max-w-none">
                                <?php echo Str::limit(strip_tags($conto->content), 100); ?>

                            </div>
                            <div class="flex justify-betweem-items-center gap-3">
                                <a href="<?php echo e(route('contos.show', $conto->id)); ?>" class="text-sm font-medium text-white bg-blue-500 rounded hover:bg-blue-600 transition p-2">
                                    Ler mais
                                </a>
                                <!--[if BLOCK]><![endif]--><?php if(auth()->check() && auth()->id() === $conto->user_id): ?>
                                    <a href="<?php echo e(route('contos.edit', $conto->id)); ?>" class="text-sm font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600 transition p-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-4.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="<?php echo e(route('contos.destroy', $conto->id)); ?>" method="POST" class="inline-block">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-sm font-medium text-white bg-red-500 rounded hover:bg-red-600 transition p-2 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-8 col-span-full">
                <p class="text-gray-500 dark:text-gray-400">Nenhum conto encontrado.</p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        <?php echo e($contos->links()); ?>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/list-contos.blade.php ENDPATH**/ ?>