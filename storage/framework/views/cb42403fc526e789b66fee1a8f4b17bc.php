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
        <!-- Sidebar Esquerdo -->
        <div class="space-y-6">
            <!-- Perfil do Usuário -->
            <div class="pb-6 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-md">
                <div class="relative h-32 bg-cover bg-center rounded-t-lg" 
                     style="background:url(<?php echo e(asset('images/users/capa.jpg')); ?>); background-size: cover; background-position: center;">
                </div>
                <div class="relative -mt-12 flex flex-col items-center">
                    <img src="<?php echo e(asset('images/users/avatar.jpg')); ?>" alt="Foto de Perfil" 
                         class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                    <h2 class="text-xl font-semibold mt-2">Nome do Usuário</h2>
                    <p class="text-gray-600">@usuario</p>
                </div>
            </div>
            
            <!-- Últimos Acessos -->
            <div class="p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-md">
                <h3 class="font-semibold text-lg mb-3">Últimos Acessos</h3>
                <ul>
                    <li class="flex items-center space-x-3 py-2 border-b">
                        <img src="<?php echo e(asset('images/users/user1.jpg')); ?>" class="w-10 h-10 rounded-full" alt="">
                        <p>Ed-Ctba - Fazenda Rio Grande</p>
                    </li>
                </ul>
            </div>
            
            <!-- Perfis Sugeridos -->
            <div class="p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-md">
                <h3 class="font-semibold text-lg mb-3">Perfis Sugeridos</h3>
                <ul>
                    <li class="flex items-center space-x-3 py-2 border-b">
                        <img src="<?php echo e(asset('images/users/user2.jpg')); ?>" class="w-10 h-10 rounded-full" alt="">
                        <p>Crisgotosinha - Londrina</p>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Feed de Postagens -->
        <div class="col-span-2 p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg">
            <form action="#" method="POST" enctype="multipart/form-data">
                <textarea name="text_content" rows="3" class="w-full p-3 border border-gray-300 rounded-lg" placeholder="Compartilhe o que você pensa..."></textarea>
                <div class="flex justify-between mt-3">
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Postar</button>
                </div>
            </form>
            
            <!-- Postagens -->
            <div class="mt-6 border-t pt-4">
                <div class="flex items-start space-x-3">
                    <img src="<?php echo e(asset('images/users/avatar.jpg')); ?>" class="w-12 h-12 rounded-full" alt="">
                    <div class="w-full">
                        <div class="flex justify-between">
                            <h4 class="font-semibold">Nome do Usuário</h4>
                            <div class="flex space-x-2">
                                <button class="text-gray-500 hover:text-red-500">❤️</button>
                                <button class="text-gray-500 hover:text-gray-900">💔</button>
                            </div>
                        </div>
                        <p class="text-gray-600">Descrição da postagem...</p>
                        <img src="<?php echo e(asset('images/posts/post1.jpg')); ?>" class="w-full mt-2 rounded-lg" alt="">
                        <div class="mt-4">
                            <input type="text" class="w-full border p-2 rounded-lg" placeholder="Escreva um comentário...">
                            <ul class="mt-2 space-y-2">
                                <li class="text-gray-600">Outro usuário: Comentário interessante!</li>
                            </ul>
                        </div>
                    </div>
                </div>
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
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/busca.blade.php ENDPATH**/ ?>