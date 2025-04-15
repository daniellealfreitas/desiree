<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Postagens')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Postagens'))]); ?>
    <h1 class="text-2xl font-bold mb-4">Postagens</h1>
    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white p-4 rounded-lg shadow mb-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-3">
                    <img src="<?php echo e(asset('storage/' . ($post->user->profile_photo_path ?? 'default.png'))); ?>" alt="Avatar" class="w-10 h-10 rounded-full">
                    <div>
                        <h3 class="font-semibold"><?php echo e($post->user->name); ?></h3>
                        <p class="text-gray-500 text-sm">{{ $post->user->email }}</p>
                    </div>
                </div>
                <form action="<?php echo e(route('likes.toggle', $post->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        <?php if($post->likes->where('user_id', auth()->id())->count() > 0): ?>
                            Descurtir
                        <?php else: ?>
                            Curtir
                        <?php endif; ?>
                    </button>
                </form>
            </div>
            <p class="text-gray-700 mb-2"><?php echo e($post->content); ?></p>
            <?php if($post->image): ?>
                <img src="<?php echo e(asset('storage/' . $post->image)); ?>" alt="Imagem do Post" class="w-full rounded-lg mb-2">
            <?php endif; ?>
            <?php if($post->video): ?>
                <video controls class="w-full rounded-lg mb-2">
                    <source src="<?php echo e(asset('storage/' . $post->video)); ?>" type="video/mp4">
                </video>
            <?php endif; ?>
            <input type="text" placeholder="Escreva um comentário..." class="w-full p-2 border rounded-lg">
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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\desiree2\resources\views/posts/index.blade.php ENDPATH**/ ?>