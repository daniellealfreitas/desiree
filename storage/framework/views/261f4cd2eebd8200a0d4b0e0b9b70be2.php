<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Dashboard'))]); ?>
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Container para Sidebar e Feed -->
        <div class="col-span-1 space-y-6">
            <!-- Sidebar Perfil -->
            <div class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
                <div class="relative h-32 bg-cover bg-center rounded-t-lg" 
                    style="background:url(<?php echo e(asset('images/users/capa.jpg')); ?>); background-size: cover; background-position: center;">
                </div>
                <div class="relative z-10 -mt-12 flex flex-col items-center">
                    <img src="<?php echo e(asset('images/users/avatar.jpg')); ?>" alt="Foto de Perfil" 
                        class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                    <h2 class="text-xl font-semibold mt-2">Nome do Usuário</h2>
                    <p class="text-gray-600">@usuario</p>
                    <div class="mt-4 flex justify-around w-full">
                        <div class="text-center">
                            <p class="text-lg font-semibold">4</p>
                            <p class="text-gray-500">Posts</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-semibold">71</p>
                            <p class="text-gray-500">Seguindo</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-semibold">0</p>
                            <p class="text-gray-500">Seguidores</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Últimos Acessos e Perfis Sugeridos -->
            <div class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
                <h3 class="text-white bg-gray-800 p-3 rounded-t-lg font-semibold">Últimos Acessos</h3>
                <ul class="p-3 space-y-2">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <li class="flex items-center space-x-3">
                            <img src="<?php echo e(asset('images/users/avatar' . $i . '.jpg')); ?>" class="w-10 h-10 rounded-full">
                            <span>Usuário <?php echo e($i); ?></span>
                        </li>
                    <?php endfor; ?>
                </ul>
                <h3 class="text-white bg-gray-800 p-3 mt-4 rounded-t-lg font-semibold">Perfis Sugeridos</h3>
                <ul class="p-3 space-y-2">
                    <?php for($i = 6; $i <= 10; $i++): ?>
                        <li class="flex items-center space-x-3">
                            <img src="<?php echo e(asset('images/users/avatar' . $i . '.jpg')); ?>" class="w-10 h-10 rounded-full">
                            <span>Usuário <?php echo e($i); ?></span>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
        <!-- Container para Feed de Postagens -->
        <div class="col-span-2 space-y-6">
            <div class="p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <textarea name="text_content" rows="3" class="w-full p-3 border border-gray-300 rounded-lg" placeholder="Compartilhe o que você pensa com fotos ou vídeos..."></textarea>
                    <div class="flex justify-between mt-3">
                        <div class="flex space-x-4">
                            <label for="image_content" class="cursor-pointer flex items-center text-gray-500">
                                📷 <input id="image_content" name="image_content" type="file" accept="image/*" class="hidden">
                            </label>
                            <label for="video_content" class="cursor-pointer flex items-center text-gray-500">
                                🎥 <input id="video_content" name="video_content" type="file" accept="video/*" class="hidden">
                            </label>
                        </div>
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Postar</button>
                    </div>
                </form>
            </div>
            <!-- Postagens -->
            
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('postfeed', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1618034876-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/dashboard.blade.php ENDPATH**/ ?>