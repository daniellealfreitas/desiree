<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => null,
    'logo' => null,
    'href' => '/',
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
    'name' => null,
    'logo' => null,
    'href' => '/',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$classes = Flux::classes()
    ->add('h-10 flex items-center mr-4')
    ;

$textClasses = Flux::classes()
    ->add('text-sm font-medium truncate [:where(&)]:text-zinc-800 dark:[:where(&)]:text-zinc-100')
    ;
?>

<?php if ($name): ?>
    <a href="<?php echo e($href); ?>" <?php echo e($attributes->class([ $classes, 'gap-2' ])->except('alt')); ?> data-flux-brand>
        <div class="size-6 rounded-sm overflow-hidden shrink-0">
            <?php if (is_string($logo)): ?>
                <img src="<?php echo e($logo); ?>" <?php echo e($attributes->only('alt')); ?> />
            <?php else: ?>
                <?php echo e($logo ?? $slot); ?>

            <?php endif; ?>
        </div>

        <div class="<?php echo e($textClasses); ?>"><?php echo e($name); ?></div>
    </a>
<?php else: ?>
    <a href="<?php echo e($href); ?>" <?php echo e($attributes->class($classes)->except('alt')); ?> data-flux-brand>
        <div class="size-8 rounded-sm overflow-hidden shrink-0">
            <?php if (is_string($logo)): ?>
                <img src="<?php echo e($logo); ?>" <?php echo e($attributes->only('alt')); ?> />
            <?php else: ?>
                <?php echo e($logo ?? $slot); ?>

            <?php endif; ?>
        </div>
    </a>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\desiree2\vendor\livewire\flux\src/../stubs/resources/views/flux/brand.blade.php ENDPATH**/ ?>