<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="container mx-auto w-full p-6">
        <?php if(isset($conto)): ?>
            <div class="shadow rounded-lg p-6">
                <h1 class="text-3xl font-bold mb-2"><?php echo e($conto->title); ?></h1>
                <span class="text-sm text-gray-500">
                    Por <?php echo e($conto->user->name ?? 'Autor desconhecido'); ?> 
                    em <?php echo e($conto->created_at->format('d/m/Y')); ?>

                </span>
                <div class="my-4">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                        <?php echo e($conto->category->name ?? 'Sem categoria'); ?>

                    </span>
                </div>
                <div class="prose max-w-none">
                    <?php echo e($conto->content); ?>

                </div>
                <?php if(auth()->check() && auth()->id() === $conto->user_id): ?>
                    <div class="mt-4 flex space-x-2">
                        <a href="<?php echo e(route('contos.edit', $conto->id)); ?>" class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600 transition">
                            Editar
                        </a>
                        <form action="<?php echo e(route('contos.destroy', $conto->id)); ?>" method="POST" class="inline-block">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded hover:bg-red-600 transition">
                                Excluir
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="bg-red-100 text-red-800 p-4 rounded">
                Conto não encontrado.
            </div>
        <?php endif; ?>
        <div class="mt-6">
            <a href="<?php echo e(route('contos')); ?>" class="text-white hover:underline">← Voltar à lista</a>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/show-conto.blade.php ENDPATH**/ ?>