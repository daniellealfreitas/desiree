<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('MindMap')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('MindMap'))]); ?>
<div class="w-full mt-6 py-14"><iframe width="100%" height="900px" frameBorder="0" src="https://www.mindmeister.com/maps/public_map_shell/3702708583/desiree-swing-club?width=100%&height=100%&z=auto&live_update=1" scrolling="no" style="overflow:hidden;margin-bottom:5px">Your browser is not able to display frames. Please visit <a href="https://www.mindmeister.com/3702708583/desiree-swing-club" target="_blank">Desiree Swing Club</a> on MindMeister.</iframe><div class="mb-5"><a href="https://www.mindmeister.com/3702708583/desiree-swing-club" target="_blank">Desiree Swing Club</a> by <a href="https://www.mindmeister.com/users/channel/42071661" target="_blank" rel="noopener noreferrer">Daniel Leal Freitas</a></div></div>
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
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/mindmap.blade.php ENDPATH**/ ?>