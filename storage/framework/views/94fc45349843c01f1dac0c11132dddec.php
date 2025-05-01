<div>
    <!--[if BLOCK]><![endif]--><?php if($errors->has('global')): ?>
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 border border-red-400">
            <?php echo e($errors->first('global')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <div id="searchform" class="p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg">
        <form wire:submit.prevent="search" class="space-y-4">
        <div class="grid grid-cols-4 gap-4">
            <div>
                <label for="id" class="block text-sm font-medium text-gray-300">ID</label>
                <input type="text" id="id" wire:model="filters.id" class="mt-1 block w-full border border-neutral-200 dark:border-neutral-700 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                <input type="text" id="username" wire:model="filters.username" class="mt-1 block w-full border border-neutral-200 dark:border-neutral-700 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label for="anuncio" class="block text-sm font-medium text-gray-300">Anúncio</label>
                <input type="text" id="anuncio" wire:model="filters.anuncio" class="mt-1 block w-full border border-neutral-200 dark:border-neutral-700 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-300">Estado</label>
                <select id="estado" wire:model.live="selectedState" class="mt-1 block w-full border border-neutral-200 dark:border-neutral-700 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 bg-zync-800 text-gray-300">
                    <option value="" class="bg-zinc-800" >Selecione</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($state->id); ?>" class="bg-zinc-800"><?php echo e($state->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
            <div>
                <label for="cidade" class="block text-sm font-medium text-gray-300">Cidade</label>
                <select id="cidade" wire:model.live="selectedCity" class="mt-1 block w-full border border-neutral-200 dark:border-neutral-700 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 bg-zync-800 text-gray-300" >
                    <option value="" class="bg-zinc-800">Selecione</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($city->id); ?>" class="bg-zinc-800"><?php echo e($city->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-red-500 text-white font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Buscar
            </button>
        </div>
        </form>
    </div>
   <!--[if BLOCK]><![endif]--><?php if($hasSearched): ?>
   <div id="results" class="p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg">
        <div class="mt-6">
            <h2 class="text-lg font-medium text-gray-300">Resultados</h2>
            <ul class="mt-4 space-y-2">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="p-4 border border-neutral-200 dark:border-neutral-700 rounded-md">
                        <p><strong>ID:</strong> <?php echo e($result->id); ?></p>
                        <p><strong>Username:</strong> <?php echo e($result->username); ?></p>
                        <p><strong>Anúncio:</strong> <?php echo e($result->anuncio); ?></p>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-gray-500">Nenhum resultado encontrado.</li>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </ul>
        </div>
    </div>
   <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/search-form.blade.php ENDPATH**/ ?>