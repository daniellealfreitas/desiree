<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'accept' => null,
    'multiple' => false,
    'error' => null,
    'id' => 'file-' . uniqid(),
    'showFilename' => true,
    'icon' => 'document-text',
    'iconVariant' => 'outline',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'label' => null,
    'accept' => null,
    'multiple' => false,
    'error' => null,
    'id' => 'file-' . uniqid(),
    'showFilename' => true,
    'icon' => 'document-text',
    'iconVariant' => 'outline',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div <?php echo e($attributes->only(['class'])->merge(['class' => 'w-full'])); ?>>
    <!--[if BLOCK]><![endif]--><?php if($label): ?>
        <label for="<?php echo e($id); ?>" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            <?php echo e($label); ?>

        </label>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="relative">
        <label for="<?php echo e($id); ?>" class="flex items-center justify-center w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
            <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => $icon,'variant' => $iconVariant,'class' => 'w-5 h-5 mr-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($iconVariant),'class' => 'w-5 h-5 mr-2']); ?>
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
            <span>Escolher arquivo<?php echo e($multiple ? 's' : ''); ?></span>
            <input
                <?php echo e($attributes->except(['class'])); ?>

                type="file"
                id="<?php echo e($id); ?>"
                class="sr-only"
                <?php if($accept): ?> accept="<?php echo e($accept); ?>" <?php endif; ?>
                <?php if($multiple): ?> multiple <?php endif; ?>
            >
        </label>
    </div>

    <!--[if BLOCK]><![endif]--><?php if($error): ?>
        <p class="mt-1 text-sm text-red-600 dark:text-red-500"><?php echo e($error); ?></p>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if($showFilename): ?>
        <div wire:loading.remove <?php echo e($attributes->wire('model')); ?>>
            <!--[if BLOCK]><![endif]--><?php if($attributes->wire('model')->value()): ?>
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400 flex items-center">
                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'document-text','class' => 'w-4 h-4 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'document-text','class' => 'w-4 h-4 mr-1']); ?>
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
                    <span class="file-name truncate">
                        <!--[if BLOCK]><![endif]--><?php if(is_array($attributes->wire('model')->value())): ?>
                            <?php echo e(count($attributes->wire('model')->value())); ?> arquivo(s) selecionado(s)
                        <?php elseif(is_object($attributes->wire('model')->value()) && method_exists($attributes->wire('model')->value(), 'getClientOriginalName')): ?>
                            <?php echo e($attributes->wire('model')->value()->getClientOriginalName()); ?>

                        <?php elseif(is_string($attributes->wire('model')->value())): ?>
                            Arquivo selecionado
                        <?php else: ?>
                            Arquivo selecionado
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </span>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div wire:loading <?php echo e($attributes->wire('model')); ?> class="mt-2">
        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
            <div class="bg-red-600 h-2 rounded-full animate-pulse" style="width: 100%"></div>
        </div>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Carregando arquivo...</div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/components/file-upload.blade.php ENDPATH**/ ?>