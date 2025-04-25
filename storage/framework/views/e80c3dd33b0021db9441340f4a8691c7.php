<div>
    <!-- Category Filter -->
    <div class="mb-6">
        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Filtrar por categoria</label>
        <select wire:model.live="selectedCategory" id="category" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm rounded-md dark:bg-zinc-800 dark:border-zinc-700">
            <option value="">Todas as categorias</option>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </select>
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
                                    <img src="<?php echo e($conto->user->userPhotos->first() ? asset($conto->user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg')); ?>"
                                         class="w-10 h-10 rounded-full">
                                    <div>
                                        <div class="font-semibold">
                                            <?php echo e($conto->user->name); ?>

                                        </div>
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