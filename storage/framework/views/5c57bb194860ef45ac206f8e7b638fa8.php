<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Grupos')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Grupos'))]); ?>
    <div class="h-full flex w-full justify-center items-center p-2">

        <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 p-4 md:p-2 xl:p-5">
    
            <!-- card  -->
            <div
                class="relative  border rounded-lg shadow-md dark:border-gray-700 transform transition duration-500 hover:scale-105">
                <div class="absolute top-3 right-3 rounded-full text-gray-100  w-6 h-6 text-center bg-zinc-600">
                    24
                </div>
                <div class="p-2 flex justify-center">
                    <a href="#">
                        <img class="rounded-md"
                        src="https://tailwindflex.com/public/images/thumbnails/coming-soon-page/thumb_u.min.webp"
                        loading="lazy">
                    </a>
                </div>
    
                <div class="px-4 pb-3">
                    <div>
                        <a href="#">
                            <h5
                                class="text-xl font-semibold tracking-tight hover:text-violet-800 dark:hover:text-violet-300 text-gray-900 dark:text-white ">
                                Sexo no mesmo ambiente
                            </h5>
                        </a>
    
                        <p class="antialiased text-gray-600 dark:text-gray-400 text-sm break-all">
                            A card component 
                        </p>
                    </div>
                </div>
    
            </div>
    
            <!-- card  -->
            <div
                class="relative  border rounded-lg shadow-md dark:border-gray-700 transform transition duration-500 hover:scale-105">
                <div class="absolute top-3 right-3 rounded-full text-gray-100 bg-zinc-600  w-6 h-6 text-center">
                    19
                </div>
    
                <div class="p-2 flex justify-center">
                    <a href="#">
                        <img class="rounded-md"
                        src="https://tailwindflex.com/public/images/thumbnails/radio-buttons/thumb_u.min.webp"
                        loading="lazy">
                    </a>
                </div>
    
                <div class="px-4 pb-3">
                    <div>
                        <a href="#">
                            <h5
                                class="text-xl font-semibold tracking-tight hover:text-violet-800 dark:hover:text-violet-300 text-gray-900 dark:text-white ">
                                Troca de casais
                            </h5>
                        </a>
    
                        <p class="antialiased text-gray-600 dark:text-gray-400 text-sm break-all">
                            description
                        </p>
                    </div>
                </div>
            </div>
    
            <!-- card  -->
            <div
                class="relative  border rounded-lg shadow-md dark:border-gray-700 transform transition duration-500 hover:scale-105">
                <div class="absolute top-3 right-3 rounded-full text-gray-100 bg-zinc-600  w-6 h-6 text-center">
                    18
                </div>
                <div class="p-2 flex justify-center">
                    <a href="#">
                        <img class="rounded-md"
                        src="https://tailwindflex.com/public/images/thumbnails/buttons-with-border-bottom/thumb_u.min.webp"
                        loading="lazy">
                    </a>
                </div>
    
                <div class="px-4 pb-3">
                    <div>
                        <a href="#">
                            <h5
                                class="text-xl font-semibold tracking-tight hover:text-violet-800 dark:hover:text-violet-300 text-gray-900 dark:text-white ">
                                Exibicionismo
                            </h5>
                        </a>
                        <p class="antialiased text-gray-600 dark:text-gray-400 text-sm break-all">
                            description
                        </p>
                    </div>
                </div>
            </div>
    
            <!-- card  -->
            <div
                class="relative  border rounded-lg shadow-md dark:border-gray-700 transform transition duration-500 hover:scale-105">
    
                <div class="absolute top-3 right-3 rounded-full text-gray-100 bg-zinc-600  w-6 h-6 text-center">
                    15
                </div>
    
                <div class="p-2 flex justify-center">
                    <a href="https://tailwindflex.com/tag/form">
                        <img class="rounded-md"
                        src="https://tailwindflex.com/public/images/thumbnails/sb-admin-2-login-page-with-tailwind/canvas.min.webp"
                        loading="lazy">
                    </a>
                </div>
    
    
                <div class="px-4 pb-3">
                    <div>
                        <a href="https://tailwindflex.com/tag/form">
                            <h5
                                class="text-xl font-semibold tracking-tight hover:text-violet-800 dark:hover:text-violet-300 text-gray-900 dark:text-white ">
                                Bondage
                            </h5>
                        </a>
    
                        <p class="antialiased text-gray-600 dark:text-gray-400 text-sm break-all">
                            description
                        </p>
    
                    </div>
                </div>
            </div>
    
            <!-- card  -->
            <div
                class="relative  border rounded-lg shadow-md dark:border-gray-700 transform transition duration-500 hover:scale-105">
                <div class="absolute top-3 right-3 rounded-full text-gray-100 bg-zinc-600  w-6 h-6 text-center">
                    10
                </div>
                <div class="p-2 flex justify-center">
                    <a href="#">
                        <img class="rounded-md"
                        src="https://tailwindflex.com/public/images/thumbnails/alert-mono-color/thumb_u.min.webp"
                        loading="lazy">
                    </a>
                </div>
    
    
                <div class="px-4 pb-3">
                    <div>
                        <a href="#">
                            <h5
                                class="text-xl font-semibold tracking-tight hover:text-violet-800 dark:hover:text-violet-300 text-gray-900 dark:text-white ">
                                Sexo em locais públicos
                            </h5>
                        </a>
    
                        <p class="antialiased text-gray-600 dark:text-gray-400 text-sm break-all">
                            description
                        </p>
                    </div>
                </div>
            </div>
    
            <!-- card  -->
            <div
                class="relative  border rounded-lg shadow-md dark:border-gray-700 transform transition duration-500 hover:scale-105">
                <div class="absolute top-3 right-3 rounded-full text-gray-100 bg-zinc-600  w-6 h-6 text-center">
                    9
                </div>
                <div class="p-2 flex justify-center">
                    <a href="#">
                        <img class="rounded-md"
                        src="https://tailwindflex.com/public/images/thumbnails/mango-gradient/canvas.min.webp"
                        loading="lazy">
                    </a>
                </div>
                <div class="px-4 pb-3">
                    <div>
                        <a href="#">
                            <h5
                                class="text-xl font-semibold tracking-tight hover:text-violet-800 dark:hover:text-violet-300 text-gray-900 dark:text-white ">
                                Fotos e Filmagens
                            </h5>
                        </a>
    
                        <p class="antialiased text-gray-600 dark:text-gray-400 text-sm break-all">
                            description
                        </p>
                    </div>
                </div>
            </div>
    
            <!-- card  -->
            <div
                class="relative  border rounded-lg shadow-md dark:border-gray-700 transform transition duration-500 hover:scale-105">
    
                <div class="absolute top-3 right-3 rounded-full text-gray-100 bg-zinc-600  w-6 h-6 text-center">
                    8
                </div>
    
                <div class="p-2 flex justify-center">
                    <a href="https://tailwindflex.com/tag/badge">
                        <img class="rounded-md"
                        src="https://tailwindflex.com/public/images/thumbnails/badges-without-border/thumb_u.min.webp"
                        loading="lazy">
                    </a>
                </div>
    
                <div class="px-4 pb-3">
                    <div>
                        <a href="https://tailwindflex.com/tag/badge">
                            <h5
                                class="text-xl font-semibold tracking-tight hover:text-violet-800 dark:hover:text-violet-300 text-gray-900 dark:text-white ">
                                Sexo Grupal
                            </h5>
                        </a>
                        <p class="antialiased text-gray-600 dark:text-gray-400 text-sm break-all">
                            description
                        </p>
                    </div>
                </div>
            </div>
    
            <!-- card  -->
            <div
                class="relative  border rounded-lg shadow-md dark:border-gray-700 transform transition duration-500 hover:scale-105">
                <div class="absolute top-3 right-3 rounded-full text-gray-100 bg-zinc-600  w-6 h-6 text-center">
                    7
                </div>
    
                <div class="p-2 flex justify-center">
                    <a href="#">
                        <img class="rounded-md"
                        src="https://tailwindflex.com/public/images/thumbnails/pagination-with-buttons/thumb_u.min.webp"
                        loading="lazy">
                    </a>
                </div>
    
    
                <div class="px-4 pb-3">
                    <div>
                        <a href="#">
                            <h5
                                class="text-xl font-semibold tracking-tight hover:text-violet-800 dark:hover:text-violet-300 text-gray-900 dark:text-white ">
                               Acessórios
                            </h5>
                        </a>
    
                        <p class="antialiased text-gray-600 dark:text-gray-400 text-sm break-all">
                            description
                        </p>
                    </div>
                </div>
    
            </div>
    
            <!-- card  -->
            <div
                class="relative  border rounded-lg shadow-md dark:border-gray-700 transform transition duration-500 hover:scale-105">
                <div class="absolute top-3 right-3 rounded-full text-gray-100 bg-zinc-600  w-6 h-6 text-center">
                    7
                </div>
    
                <div class="p-2 flex justify-center">
                    <a href="#">
                        <img class="rounded-md"
                        src="https://tailwindflex.com/public/images/thumbnails/resonsive-card-grid-with-hover-animation/thumb_u.min.webp"
                        loading="lazy">
                    </a>
                </div>
    
                <div class="px-4 pb-3">
                    <div>
                        <a href="#">
                            <h5
                                class="text-xl font-semibold tracking-tight hover:text-violet-800 dark:hover:text-violet-300 text-gray-900 dark:text-white ">
                                Dominação
                            </h5>
                        </a>
                        <p class="antialiased text-gray-600 dark:text-gray-400 text-sm break-all">
                            description
                        </p>
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
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/grupos.blade.php ENDPATH**/ ?>