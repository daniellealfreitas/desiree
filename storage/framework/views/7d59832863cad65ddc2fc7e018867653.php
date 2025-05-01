<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
    <head>
        <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <?php
            $latestPhoto = auth()->user()->userPhotos()->latest()->first();
            $avatarUrl = $latestPhoto ? asset($latestPhoto->photo_path) : asset('images/default-avatar.jpg');
        ?>

        <?php if (isset($component)) { $__componentOriginale96c14d638c792103c11b984a4ed1896 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale96c14d638c792103c11b984a4ed1896 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::header','data' => ['container' => true,'class' => 'bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['container' => true,'class' => 'bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700']); ?>
            <?php if (isset($component)) { $__componentOriginal1b6467b07b302021134396bbd98e74a9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1b6467b07b302021134396bbd98e74a9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.toggle','data' => ['class' => 'lg:hidden','icon' => 'bars-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'lg:hidden','icon' => 'bars-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1b6467b07b302021134396bbd98e74a9)): ?>
<?php $attributes = $__attributesOriginal1b6467b07b302021134396bbd98e74a9; ?>
<?php unset($__attributesOriginal1b6467b07b302021134396bbd98e74a9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1b6467b07b302021134396bbd98e74a9)): ?>
<?php $component = $__componentOriginal1b6467b07b302021134396bbd98e74a9; ?>
<?php unset($__componentOriginal1b6467b07b302021134396bbd98e74a9); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginald33a3439ec8f8da64b388b23a8637b39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald33a3439ec8f8da64b388b23a8637b39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navbar.index','data' => ['class' => '-mb-px max-lg:hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '-mb-px max-lg:hidden']); ?>
                <?php if (isset($component)) { $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navbar.item','data' => ['icon' => 'map-pin','href' => route('radar')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navbar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'map-pin','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('radar'))]); ?>Radar <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $attributes = $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $component = $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navbar.item','data' => ['icon' => 'inbox','badge' => '12','href' => route('messages.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navbar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'inbox','badge' => '12','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('messages.index'))]); ?>Mensagens <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $attributes = $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $component = $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('follow-request-notifications', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1108464796-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('notifications', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1108464796-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald33a3439ec8f8da64b388b23a8637b39)): ?>
<?php $attributes = $__attributesOriginald33a3439ec8f8da64b388b23a8637b39; ?>
<?php unset($__attributesOriginald33a3439ec8f8da64b388b23a8637b39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald33a3439ec8f8da64b388b23a8637b39)): ?>
<?php $component = $__componentOriginald33a3439ec8f8da64b388b23a8637b39; ?>
<?php unset($__componentOriginald33a3439ec8f8da64b388b23a8637b39); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal4a4f7aa062a095c651c2f80bb685a42a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::spacer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::spacer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a)): ?>
<?php $attributes = $__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a; ?>
<?php unset($__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4a4f7aa062a095c651c2f80bb685a42a)): ?>
<?php $component = $__componentOriginal4a4f7aa062a095c651c2f80bb685a42a; ?>
<?php unset($__componentOriginal4a4f7aa062a095c651c2f80bb685a42a); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginald33a3439ec8f8da64b388b23a8637b39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald33a3439ec8f8da64b388b23a8637b39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navbar.index','data' => ['class' => 'me-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'me-4']); ?>
                <?php if (isset($component)) { $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navbar.item','data' => ['icon' => 'magnifying-glass','href' => '#','label' => 'Search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navbar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'magnifying-glass','href' => '#','label' => 'Search']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $attributes = $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $component = $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navbar.item','data' => ['class' => 'max-lg:hidden','icon' => 'cog-6-tooth','href' => route('settings.profile'),'label' => 'Settings']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navbar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'max-lg:hidden','icon' => 'cog-6-tooth','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings.profile')),'label' => 'Settings']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $attributes = $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $component = $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navbar.item','data' => ['class' => 'max-lg:hidden','icon' => 'information-circle','href' => '#','label' => 'Help']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navbar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'max-lg:hidden','icon' => 'information-circle','href' => '#','label' => 'Help']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $attributes = $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $component = $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald33a3439ec8f8da64b388b23a8637b39)): ?>
<?php $attributes = $__attributesOriginald33a3439ec8f8da64b388b23a8637b39; ?>
<?php unset($__attributesOriginald33a3439ec8f8da64b388b23a8637b39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald33a3439ec8f8da64b388b23a8637b39)): ?>
<?php $component = $__componentOriginald33a3439ec8f8da64b388b23a8637b39; ?>
<?php unset($__componentOriginald33a3439ec8f8da64b388b23a8637b39); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::dropdown','data' => ['position' => 'top','align' => 'start']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'top','align' => 'start']); ?>
                <?php if (isset($component)) { $__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::profile','data' => ['avatar' => $avatarUrl]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::profile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['avatar' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avatarUrl)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994)): ?>
<?php $attributes = $__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994; ?>
<?php unset($__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994)): ?>
<?php $component = $__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994; ?>
<?php unset($__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.group','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal763facc893e4de8b6e8106f07c0fccb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal763facc893e4de8b6e8106f07c0fccb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.index','data' => ['checked' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['checked' => true]); ?><?php echo e(auth()->user()->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal763facc893e4de8b6e8106f07c0fccb4)): ?>
<?php $attributes = $__attributesOriginal763facc893e4de8b6e8106f07c0fccb4; ?>
<?php unset($__attributesOriginal763facc893e4de8b6e8106f07c0fccb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal763facc893e4de8b6e8106f07c0fccb4)): ?>
<?php $component = $__componentOriginal763facc893e4de8b6e8106f07c0fccb4; ?>
<?php unset($__componentOriginal763facc893e4de8b6e8106f07c0fccb4); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $attributes = $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $component = $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
                    
                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['icon' => 'arrow-right-start-on-rectangle','href' => '/' . auth()->user()->username]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'arrow-right-start-on-rectangle','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('/' . auth()->user()->username)]); ?>Meu Perfil <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['icon' => 'user-plus','href' => route('follow.requests'),'wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'user-plus','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('follow.requests')),'wire:navigate' => true]); ?>
                        Solicitações para seguir
                        <?php
                            $pendingCount = \App\Models\FollowRequest::where('receiver_id', auth()->id())
                                ->where('status', 'pending')
                                ->count();
                        ?>
                        <?php if($pendingCount > 0): ?>
                            <span class="ml-2 px-2 py-0.5 text-xs bg-red-500 text-white rounded-full">
                                <?php echo e($pendingCount); ?>

                            </span>
                        <?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['icon' => 'arrow-right-start-on-rectangle','href' => route('settings.profile')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'arrow-right-start-on-rectangle','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings.profile'))]); ?>Configurações <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['icon' => 'arrow-right-start-on-rectangle','href' => '#']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'arrow-right-start-on-rectangle','href' => '#']); ?>Meus Visitantes <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['icon' => 'arrow-right-start-on-rectangle','href' => route('renovar-vip')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'arrow-right-start-on-rectangle','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('renovar-vip'))]); ?>Renovar VIP <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['icon' => 'credit-card','href' => route('meus-pagamentos')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'credit-card','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('meus-pagamentos'))]); ?>Meus Pagamentos <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['as' => 'button','type' => 'submit','icon' => 'arrow-right-start-on-rectangle','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'button','type' => 'submit','icon' => 'arrow-right-start-on-rectangle','class' => 'w-full']); ?>
                            <?php echo e(__('Sair')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    </form>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $attributes = $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $component = $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $attributes = $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $component = $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale96c14d638c792103c11b984a4ed1896)): ?>
<?php $attributes = $__attributesOriginale96c14d638c792103c11b984a4ed1896; ?>
<?php unset($__attributesOriginale96c14d638c792103c11b984a4ed1896); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale96c14d638c792103c11b984a4ed1896)): ?>
<?php $component = $__componentOriginale96c14d638c792103c11b984a4ed1896; ?>
<?php unset($__componentOriginale96c14d638c792103c11b984a4ed1896); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal17e56bc23bb0192e474b351c4358d446 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal17e56bc23bb0192e474b351c4358d446 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.index','data' => ['sticky' => true,'stashable' => true,'class' => 'border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sticky' => true,'stashable' => true,'class' => 'border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900']); ?>
            <?php if (isset($component)) { $__componentOriginal1b6467b07b302021134396bbd98e74a9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1b6467b07b302021134396bbd98e74a9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.toggle','data' => ['class' => 'lg:hidden','icon' => 'x-mark']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'lg:hidden','icon' => 'x-mark']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1b6467b07b302021134396bbd98e74a9)): ?>
<?php $attributes = $__attributesOriginal1b6467b07b302021134396bbd98e74a9; ?>
<?php unset($__attributesOriginal1b6467b07b302021134396bbd98e74a9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1b6467b07b302021134396bbd98e74a9)): ?>
<?php $component = $__componentOriginal1b6467b07b302021134396bbd98e74a9; ?>
<?php unset($__componentOriginal1b6467b07b302021134396bbd98e74a9); ?>
<?php endif; ?>

            <a href="<?php echo e(route('dashboard')); ?>" class="mr-5 flex items-center space-x-2" wire:navigate>
                <?php if (isset($component)) { $__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3)): ?>
<?php $attributes = $__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3; ?>
<?php unset($__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3)): ?>
<?php $component = $__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3; ?>
<?php unset($__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3); ?>
<?php endif; ?>
            </a>

            <?php if (isset($component)) { $__componentOriginalacac6a48a34186ea0abd369a00e5e2d4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalacac6a48a34186ea0abd369a00e5e2d4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.index','data' => ['variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline']); ?>
                <?php if (isset($component)) { $__componentOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.group','data' => ['class' => 'grid']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'grid']); ?>
                    <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'home','href' => route('dashboard'),'current' => request()->routeIs('dashboard'),'wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'home','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard')),'current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('dashboard')),'wire:navigate' => true]); ?>
                        <?php echo e(__('Principal')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'magnifying-glass-circle','href' => route('busca'),'current' => request()->routeIs('busca'),'wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'magnifying-glass-circle','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('busca')),'current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('busca')),'wire:navigate' => true]); ?>
                        <?php echo e(__('Busca')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'book-open','href' => route('contos'),'current' => request()->routeIs('contos'),'wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'book-open','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contos')),'current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('contos')),'wire:navigate' => true]); ?>
                        <?php echo e(__('Contos')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.group','data' => ['expandable' => true,'heading' => 'Feed','class' => ' lg:grid']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['expandable' => true,'heading' => 'Feed','class' => ' lg:grid']); ?>
                        <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'photo','href' => route('feed_imagens')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'photo','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('feed_imagens'))]); ?>Imagens <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'video-camera','href' => route('feed_videos')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'video-camera','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('feed_videos'))]); ?> Vídeos <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>                        
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4)): ?>
<?php $attributes = $__attributesOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4; ?>
<?php unset($__attributesOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4)): ?>
<?php $component = $__componentOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4; ?>
<?php unset($__componentOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4); ?>
<?php endif; ?> 

                    <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'calendar-days','href' => route('programacao'),'current' => request()->routeIs('programacao'),'wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'calendar-days','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('programacao')),'current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('programacao')),'wire:navigate' => true]); ?>
                        <?php echo e(__('Programação')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'map-pin','href' => route('radar'),'current' => request()->routeIs('radar'),'wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'map-pin','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('radar')),'current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('radar')),'wire:navigate' => true]); ?>
                        <?php echo e(__('Radar')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'user-group','href' => route('grupos'),'current' => request()->routeIs('grupos'),'wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'user-group','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('grupos')),'current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('grupos')),'wire:navigate' => true]); ?>
                        <?php echo e(__('Grupos')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'chat-bubble-left-right','href' => route('bate_papo'),'current' => request()->routeIs('bate_papo'),'wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'chat-bubble-left-right','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('bate_papo')),'current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('bate_papo')),'wire:navigate' => true]); ?>
                        <?php echo e(__('Bate Papo')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'inbox','badge' => '12','href' => route('caixa_de_mensagens'),'current' => request()->routeIs('caixa_de_mensagens'),'wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'inbox','badge' => '12','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('caixa_de_mensagens')),'current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('caixa_de_mensagens')),'wire:navigate' => true]); ?>
                        <?php echo e(__('Caixa de Mensagens')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4)): ?>
<?php $attributes = $__attributesOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4; ?>
<?php unset($__attributesOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4)): ?>
<?php $component = $__componentOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4; ?>
<?php unset($__componentOriginal8b1fe5c87f0876e7c101dbc6fe82a9a4); ?>
<?php endif; ?>                
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalacac6a48a34186ea0abd369a00e5e2d4)): ?>
<?php $attributes = $__attributesOriginalacac6a48a34186ea0abd369a00e5e2d4; ?>
<?php unset($__attributesOriginalacac6a48a34186ea0abd369a00e5e2d4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalacac6a48a34186ea0abd369a00e5e2d4)): ?>
<?php $component = $__componentOriginalacac6a48a34186ea0abd369a00e5e2d4; ?>
<?php unset($__componentOriginalacac6a48a34186ea0abd369a00e5e2d4); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal4a4f7aa062a095c651c2f80bb685a42a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::spacer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::spacer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a)): ?>
<?php $attributes = $__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a; ?>
<?php unset($__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4a4f7aa062a095c651c2f80bb685a42a)): ?>
<?php $component = $__componentOriginal4a4f7aa062a095c651c2f80bb685a42a; ?>
<?php unset($__componentOriginal4a4f7aa062a095c651c2f80bb685a42a); ?>
<?php endif; ?>    
        
            <!-- Desktop User Menu -->
            <?php if (isset($component)) { $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::dropdown','data' => ['position' => 'bottom','align' => 'start']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'bottom','align' => 'start']); ?>
                <?php if (isset($component)) { $__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::profile','data' => ['name' => auth()->user()->name,'avatar' => $avatarUrl,'iconTrailing' => 'chevrons-up-down']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::profile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->name),'avatar' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avatarUrl),'icon-trailing' => 'chevrons-up-down']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994)): ?>
<?php $attributes = $__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994; ?>
<?php unset($__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994)): ?>
<?php $component = $__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994; ?>
<?php unset($__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.index','data' => ['class' => 'w-[220px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-[220px]']); ?>
                    <?php if (isset($component)) { $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.group','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <img src="<?php echo e($avatarUrl); ?>" 
                                         class="h-full w-full object-cover" 
                                         alt="<?php echo e(auth()->user()->name); ?>">
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold"><?php echo e(auth()->user()->name); ?></span>
                                    <span class="truncate text-xs"><?php echo e(auth()->user()->email); ?></span>
                                </div>
                            </div>
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $attributes = $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $component = $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.group','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['href' => route('settings.profile'),'icon' => 'cog','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings.profile')),'icon' => 'cog','wire:navigate' => true]); ?><?php echo e(__('Configurações')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $attributes = $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $component = $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['as' => 'button','type' => 'submit','icon' => 'arrow-right-start-on-rectangle','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'button','type' => 'submit','icon' => 'arrow-right-start-on-rectangle','class' => 'w-full']); ?>
                            <?php echo e(__('Sair')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    </form>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $attributes = $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $component = $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $attributes = $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $component = $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal17e56bc23bb0192e474b351c4358d446)): ?>
<?php $attributes = $__attributesOriginal17e56bc23bb0192e474b351c4358d446; ?>
<?php unset($__attributesOriginal17e56bc23bb0192e474b351c4358d446); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal17e56bc23bb0192e474b351c4358d446)): ?>
<?php $component = $__componentOriginal17e56bc23bb0192e474b351c4358d446; ?>
<?php unset($__componentOriginal17e56bc23bb0192e474b351c4358d446); ?>
<?php endif; ?>

        <!-- Mobile User Menu -->
        <?php if (isset($component)) { $__componentOriginale96c14d638c792103c11b984a4ed1896 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale96c14d638c792103c11b984a4ed1896 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::header','data' => ['class' => 'lg:hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'lg:hidden']); ?>
            <?php if (isset($component)) { $__componentOriginal1b6467b07b302021134396bbd98e74a9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1b6467b07b302021134396bbd98e74a9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.toggle','data' => ['class' => 'lg:hidden','icon' => 'bars-2','inset' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'lg:hidden','icon' => 'bars-2','inset' => 'left']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1b6467b07b302021134396bbd98e74a9)): ?>
<?php $attributes = $__attributesOriginal1b6467b07b302021134396bbd98e74a9; ?>
<?php unset($__attributesOriginal1b6467b07b302021134396bbd98e74a9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1b6467b07b302021134396bbd98e74a9)): ?>
<?php $component = $__componentOriginal1b6467b07b302021134396bbd98e74a9; ?>
<?php unset($__componentOriginal1b6467b07b302021134396bbd98e74a9); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal4a4f7aa062a095c651c2f80bb685a42a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::spacer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::spacer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a)): ?>
<?php $attributes = $__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a; ?>
<?php unset($__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4a4f7aa062a095c651c2f80bb685a42a)): ?>
<?php $component = $__componentOriginal4a4f7aa062a095c651c2f80bb685a42a; ?>
<?php unset($__componentOriginal4a4f7aa062a095c651c2f80bb685a42a); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::dropdown','data' => ['position' => 'top','align' => 'start']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'top','align' => 'start']); ?>
                <?php if (isset($component)) { $__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::profile','data' => ['initials' => auth()->user()->initials(),'avatar' => $avatarUrl,'iconTrailing' => 'chevron-down']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::profile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['initials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->initials()),'avatar' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avatarUrl),'icon-trailing' => 'chevron-down']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994)): ?>
<?php $attributes = $__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994; ?>
<?php unset($__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994)): ?>
<?php $component = $__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994; ?>
<?php unset($__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.group','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <img src="<?php echo e($avatarUrl); ?>" 
                                         class="h-full w-full object-cover" 
                                         alt="<?php echo e(auth()->user()->name); ?>">
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold"><?php echo e(auth()->user()->name); ?></span>
                                    <span class="truncate text-xs"><?php echo e(auth()->user()->email); ?></span>
                                </div>
                            </div>
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $attributes = $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $component = $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.group','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['href' => route('settings.profile'),'icon' => 'cog','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings.profile')),'icon' => 'cog','wire:navigate' => true]); ?><?php echo e(__('Config')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $attributes = $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $component = $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['as' => 'button','type' => 'submit','icon' => 'arrow-right-start-on-rectangle','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'button','type' => 'submit','icon' => 'arrow-right-start-on-rectangle','class' => 'w-full']); ?>
                            <?php echo e(__('Sair')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    </form>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $attributes = $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $component = $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $attributes = $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $component = $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale96c14d638c792103c11b984a4ed1896)): ?>
<?php $attributes = $__attributesOriginale96c14d638c792103c11b984a4ed1896; ?>
<?php unset($__attributesOriginale96c14d638c792103c11b984a4ed1896); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale96c14d638c792103c11b984a4ed1896)): ?>
<?php $component = $__componentOriginale96c14d638c792103c11b984a4ed1896; ?>
<?php unset($__componentOriginale96c14d638c792103c11b984a4ed1896); ?>
<?php endif; ?>

        <?php echo e($slot); ?>


        <?php app('livewire')->forceAssetInjection(); ?>
<?php echo app('flux')->scripts(); ?>


        <script>
            // Function to trigger confetti animation
            window.triggerConfetti = function() {
                // Create a canvas element dynamically
                const canvas = document.createElement('canvas');
                document.body.appendChild(canvas);
                canvas.style.position = 'fixed';
                canvas.style.top = '0';
                canvas.style.left = '0';
                canvas.style.width = '100%';
                canvas.style.height = '100%';
                canvas.style.pointerEvents = 'none';
                const ctx = canvas.getContext('2d');
                const confettiCount = 300;
                const confetti = [];

                // Initialize confetti particles
                for (let i = 0; i < confettiCount; i++) {
                    confetti.push({
                        x: Math.random() * canvas.width,
                        y: Math.random() * canvas.height - canvas.height,
                        r: Math.random() * 6 + 2,
                        dx: Math.random() * 4 - 2,
                        dy: Math.random() * 4 + 2,
                        color: `hsl(${Math.random() * 360}, 100%, 50%)`
                    });
                }

                // Resize canvas to match the window size
                function resizeCanvas() {
                    canvas.width = window.innerWidth;
                    canvas.height = window.innerHeight;
                }
                resizeCanvas();
                window.addEventListener('resize', resizeCanvas);

                // Animation loop
                function animate() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    confetti.forEach(p => {
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                        ctx.fillStyle = p.color;
                        ctx.fill();
                        p.x += p.dx;
                        p.y += p.dy;
                        if (p.y > canvas.height) p.y = -p.r;
                    });
                    requestAnimationFrame(animate);
                }
                animate();

                // Remove canvas after 2 seconds
                setTimeout(() => {
                    window.removeEventListener('resize', resizeCanvas);
                    canvas.remove();
                }, 5000);
            };

            // Function to trigger XP popup
            window.triggerXpPopup = function(points) {
                // Create a popup element dynamically
                const popup = document.createElement('div');
                popup.textContent = `+${points} XP!`;
                popup.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-4xl font-bold bg-blue-500 px-6 py-3 rounded-lg shadow-lg animate-pulse';
                document.body.appendChild(popup);

                // Remove popup after 2 seconds
                setTimeout(() => {
                    popup.remove();
                }, 5000);
            };

            // Add keyboard shortcut to trigger animations
            document.addEventListener('keydown', function(event) {
                if (event.key === 'F10') {
                    // Trigger both animations when F10 is pressed
                    window.triggerConfetti();
                    window.triggerXpPopup(50); // Example: 50 XP
                }
            });
        </script>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/components/layouts/app/sidebar.blade.php ENDPATH**/ ?>