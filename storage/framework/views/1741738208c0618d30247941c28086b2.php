<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => $post->title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->title)]); ?>
    <div class=" h-screen h-full py-2 sm:py-8 lg:py-2">
        <div class="mx-auto max-w-screen-md px-4 md:px-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo e($post->title); ?></h1>
            <img src="<?php echo e(asset($post->image)); ?>" alt="<?php echo e($post->title); ?>" class="mt-4 rounded-lg shadow-lg">
            <p class="mt-6 text-gray-700 dark:text-gray-300"><?php echo e($post->content); ?></p>
            <?php if($post->video): ?>
                <div class="mt-4">
                    <video controls class="w-full rounded-lg shadow-lg">
                        <source src="<?php echo e(asset($post->video)); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php endif; ?>
            <div class="mt-2 text-xs text-gray-400">
                <span>Created: <?php echo e($post->created_at->format('M d, Y H:i')); ?></span>
                <?php if($post->updated_at && $post->updated_at != $post->created_at): ?>
                    <span class="ml-2">Updated: <?php echo e($post->updated_at->format('M d, Y H:i')); ?></span>
                <?php endif; ?>
            </div>
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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\desiree2\resources\views/post/show.blade.php ENDPATH**/ ?>