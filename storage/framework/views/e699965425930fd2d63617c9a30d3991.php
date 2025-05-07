<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Contos')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Contos'))]); ?>

    <div class="container mx-auto max-w-6xl p-4">
        <!-- Mensagem de sucesso -->
        <?php if(session()->has('message')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?>

        <!-- Botão para abrir o modal -->
        <div class="flex justify-end mb-4">
            <button 
                x-data 
                x-on:click="$dispatch('open-modal')" 
                class="bg-purple-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-purple-700 hover:shadow-lg transition duration-300 ease-in-out">
                <?php echo e(__('Criar Conto')); ?>

            </button>
        </div>

        <!-- Lista de contos -->
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('list-contos', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1862919999-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>

    <!-- Modal -->
    <div 
        x-data="{show : false}"
        x-show="show"
        x-on:open-modal.window="show = true"
        x-on:close-modal.window="show = false"
        x-on:keydown.escape.window="show = false"
        x-on:click.away="show = false"
        class="fixed inset-0 flex items-center justify-center bg-zinc-800 z-50">
        <div class="rounded-lg shadow-lg p-6 w-full max-w-lg">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('create-conto', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1862919999-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
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
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/contos.blade.php ENDPATH**/ ?>