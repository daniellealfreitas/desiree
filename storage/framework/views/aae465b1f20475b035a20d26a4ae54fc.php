<div class="flex flex-col w-full" wire:poll.10s>
    <!-- Filtro de distância -->
    <div class="w-full bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 mb-6">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-semibold">Filtro de Distância</h3>
            <div class="flex items-center gap-2">
                <div class="flex items-center text-sm text-gray-500">
                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'map-pin','class' => 'w-4 h-4 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'map-pin','class' => 'w-4 h-4 mr-1']); ?>
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
                    <span>Localização atualizada automaticamente</span>
                </div>
                <button
                    wire:click="reloadCandidates"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                    class="text-sm text-blue-500 hover:text-blue-600 flex items-center"
                >
                    <span wire:loading.remove wire:target="reloadCandidates">
                        <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'arrow-path','class' => 'w-4 h-4 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'arrow-path','class' => 'w-4 h-4 mr-1']); ?>
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
                        Recarregar
                    </span>
                    <span wire:loading wire:target="reloadCandidates">
                        <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Carregando...
                    </span>
                </button>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <input
                type="range"
                min="1"
                max="500"
                wire:model.live="maxDistance"
                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
            >
            <span class="text-sm font-medium"><?php echo e($maxDistance); ?> km</span>
        </div>
    </div>

    <!-- Mensagem de erro de localização -->
    <!--[if BLOCK]><![endif]--><?php if($showLocationError): ?>
        <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 relative" role="alert">
            <strong class="font-bold">Atenção!</strong>
            <span class="block sm:inline"><?php echo e($locationErrorMessage); ?></span>
            <!--[if BLOCK]><![endif]--><?php if(!auth()->user()->latitude || !auth()->user()->longitude): ?>
                <div class="mt-2">
                    <p class="text-sm mb-2">
                        Sua localização será detectada automaticamente quando você permitir o acesso à sua localização no navegador.
                    </p>
                    <p class="text-sm">
                        Você também pode atualizar manualmente sua localização nas configurações do perfil:
                    </p>
                    <a href="<?php echo e(route('settings.profile')); ?>" class="mt-2 inline-block bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-4 rounded">
                        Ir para Configurações
                    </a>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Layout de duas colunas -->
    <div class="flex flex-col md:flex-row w-full gap-6">
        <!-- Coluna esquerda: Usuário atual -->
        <div class="w-full md:w-2/5">
        <!--[if BLOCK]><![endif]--><?php if($currentUser && isset($currentUser['user'])): ?>
            <div id="user_match" class="p-6 rounded-xl shadow-md w-full text-center h-full"
                wire:key="current-user-<?php echo e($currentUser['user']->id ?? 'none'); ?>">
            
            <!--[if BLOCK]><![endif]--><?php if($currentUser['user']->photos && $currentUser['user']->photos->count()): ?>
                <div x-data="{ idx: 0, total: <?php echo e($currentUser['user']->photos->count()); ?> }"
                    class="mb-4 flex flex-col items-center justify-center" >
                    <div class="relative">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currentUser['user']->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <img
                                x-show="idx === <?php echo e($loop->index); ?>"
                                src="<?php echo e(asset('storage/' . $photo->photo_path)); ?>"
                                alt="Foto de <?php echo e($currentUser['user']->name); ?>"
                                class="h-60 w-60 rounded-full object-cover border-2 <?php echo e($currentUser['hasMatched'] ? 'border-green-400' : ($currentUser['hasLiked'] ? 'border-pink-400' : 'border-gray-400')); ?> mx-auto"
                                loading="lazy"
                                style="display: none;"
                                x-bind:style="idx === <?php echo e($loop->index); ?> ? 'display:block' : 'display:none'"
                            >
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($currentUser['user']->photos->count() > 1): ?>
                        <div class="absolute inset-0 flex items-center justify-between px-1">
                            <button
                                @click="idx = (idx === 0 ? total-1 : idx-1)"
                                class="bg-white/80 rounded-full px-2 py-0.5 shadow text-gray-800"
                                type="button">&lt;</button>
                            <button
                                @click="idx = (idx === total-1 ? 0 : idx+1)"
                                class="bg-white/80 rounded-full px-2 py-0.5 shadow text-gray-800"
                                type="button">&gt;</button>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    
                    <div class="flex gap-1 mt-2" x-show="total > 1">
                        <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < $currentUser['user']->photos->count(); $i++): ?>
                            <span
                                :class="{'bg-pink-400': idx === <?php echo e($i); ?>, 'bg-gray-300': idx !== <?php echo e($i); ?>}"
                                class="w-2 h-2 rounded-full block"
                            ></span>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            <?php elseif($currentUser['user']->userPhoto): ?>
                <img src="<?php echo e(asset($currentUser['user']->userPhoto)); ?>"
                     alt="Foto de <?php echo e($currentUser['user']->name); ?>"
                     class="h-60 w-60 rounded-full object-cover mx-auto mb-4 border-2 <?php echo e($currentUser['hasMatched'] ? 'border-green-400' : ($currentUser['hasLiked'] ? 'border-pink-400' : 'border-gray-400')); ?>">
            <?php else: ?>
                <span class="flex h-60 w-60 rounded-full bg-gray-200 items-center justify-center text-4xl text-gray-400 mb-4 mx-auto border-2 <?php echo e($currentUser['hasMatched'] ? 'border-green-400' : ($currentUser['hasLiked'] ? 'border-pink-400' : 'border-gray-400')); ?>">
                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.42 0 4.675.573 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <h2 class="text-xl font-bold"><?php echo e($currentUser['user']->name); ?></h2>
            <a href="<?php echo e($currentUser['user']->username); ?>" class="text-blue-500 hover:text-blue-600"><?php echo e($currentUser['user']->username); ?></a>

            <!-- Status de match -->
            <!--[if BLOCK]><![endif]--><?php if($currentUser['hasMatched']): ?>
                <div class="inline-flex items-center px-3 py-1 mt-2 bg-green-100 text-green-800 rounded-full">
                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'check-badge','class' => 'w-4 h-4 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'check-badge','class' => 'w-4 h-4 mr-1']); ?>
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
                    <span class="text-sm font-medium">Match</span>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Distância com ícone -->
            <div class="flex items-center justify-center mt-2 text-gray-500">
                <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'map-pin','class' => 'w-4 h-4 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'map-pin','class' => 'w-4 h-4 mr-1']); ?>
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
                <p class="text-sm"><?php echo e(number_format($currentUser['user']->distance, 1)); ?> km de distância</p>
            </div>

            <div class="flex justify-center mt-6 gap-4">
                <button
                    wire:click="pass()"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="<?php echo e($currentUser['hasPassed'] ? 'bg-red-500 text-white' : 'bg-gray-300 text-black hover:bg-gray-400'); ?> font-bold py-3 px-6 rounded-full flex items-center"
                >
                    <span wire:loading.remove wire:target="pass">
                        <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'x-mark','class' => 'w-5 h-5 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'x-mark','class' => 'w-5 h-5 mr-1']); ?>
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
                        <?php echo e($currentUser['hasPassed'] ? 'Passado' : 'Pass'); ?>

                    </span>
                    <span wire:loading wire:target="pass">
                        <svg class="animate-spin h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processando...
                    </span>
                </button>
                <button
                    wire:click="like()"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="<?php echo e($currentUser['hasLiked'] ? 'bg-pink-600' : 'bg-pink-500 hover:bg-pink-600'); ?> text-white font-bold py-3 px-6 rounded-full flex items-center"
                >
                    <span wire:loading.remove wire:target="like">
                        <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'heart','variant' => ''.e($currentUser['hasLiked'] ? 'solid' : 'outline').'','class' => 'w-5 h-5 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heart','variant' => ''.e($currentUser['hasLiked'] ? 'solid' : 'outline').'','class' => 'w-5 h-5 mr-1']); ?>
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
                        <?php echo e($currentUser['hasLiked'] ? 'Curtido' : 'Like'); ?>

                    </span>
                    <span wire:loading wire:target="like">
                        <svg class="animate-spin h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processando...
                    </span>
                </button>
            </div>

            <!-- Botões para navegação manual e recarga -->
            <div class="mt-4 text-center flex justify-center gap-4">
                <button
                    wire:click="nextUser()"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                    class="text-sm text-gray-500 hover:text-gray-700 flex items-center"
                >
                    <span wire:loading.remove wire:target="nextUser">
                        <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'arrow-right','class' => 'w-4 h-4 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'arrow-right','class' => 'w-4 h-4 mr-1']); ?>
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
                        Próximo perfil
                    </span>
                    <span wire:loading wire:target="nextUser">
                        <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Carregando...
                    </span>
                </button>

                <button
                    wire:click="$refresh"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                    class="text-sm text-blue-500 hover:text-blue-600 flex items-center"
                >
                    <span wire:loading.remove wire:target="$refresh">
                        <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'arrow-path','class' => 'w-4 h-4 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'arrow-path','class' => 'w-4 h-4 mr-1']); ?>
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
                        Atualizar
                    </span>
                    <span wire:loading wire:target="$refresh">
                        <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Atualizando...
                    </span>
                </button>
            </div>
        </div>

    <?php elseif(!$showLocationError): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center h-full">
            <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'map','class' => 'w-16 h-16 mx-auto text-gray-400 mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'map','class' => 'w-16 h-16 mx-auto text-gray-400 mb-4']); ?>
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
            <p class="text-gray-500 text-lg">Você já viu todo mundo por perto 🧭</p>
            <p class="text-gray-400 text-sm mt-2">Tente aumentar a distância máxima ou volte mais tarde.</p>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- Coluna direita: Usuários curtidos e lista de usuários próximos -->
        <div class="w-full md:w-3/5">
            <!-- Lista de usuários curtidos -->
            <!--[if BLOCK]><![endif]--><?php if(count($likedUsers) > 0): ?>
            <div id="liked_users" class="p-6 rounded-xl shadow-md w-full bg-white dark:bg-gray-800 mb-6"
                wire:key="liked-users-<?php echo e(count($likedUsers)); ?>">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Usuários que você curtiu:</h3>
                    <span class="text-sm text-gray-500"><?php echo e(count($likedUsers)); ?> usuário(s)</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $likedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $likedData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $likedUser = $likedData['user']; ?>
                        <div wire:key="liked-user-<?php echo e($likedUser->id); ?>" class="flex flex-col items-center">
                            <div class="relative">
                                <!--[if BLOCK]><![endif]--><?php if($likedUser->photos && $likedUser->photos->count()): ?>
                                    <img src="<?php echo e(asset('storage/' . $likedUser->photos->first()->photo_path)); ?>"
                                        alt="Foto de <?php echo e($likedUser->name); ?>"
                                        class="h-14 w-14 rounded-full object-cover border-2 <?php echo e($likedData['hasMatched'] ? 'border-green-400' : 'border-pink-400'); ?>">
                                <?php elseif($likedUser->userPhoto): ?>
                                    <img src="<?php echo e(Storage::url($likedUser->userPhoto)); ?>"
                                        alt="Foto de <?php echo e($likedUser->name); ?>"
                                        class="h-14 w-14 rounded-full object-cover border-2 <?php echo e($likedData['hasMatched'] ? 'border-green-400' : 'border-pink-400'); ?>">
                                <?php else: ?>
                                    <span class="flex h-14 w-14 rounded-full bg-gray-200 items-center justify-center text-2xl text-gray-400 border-2 <?php echo e($likedData['hasMatched'] ? 'border-green-400' : 'border-pink-400'); ?>">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.42 0 4.675.573 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!--[if BLOCK]><![endif]--><?php if($likedData['hasMatched']): ?>
                                    <span class="absolute -top-1 -right-1 bg-green-500 rounded-full p-1">
                                        <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'check-badge','class' => 'w-3 h-3 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'check-badge','class' => 'w-3 h-3 text-white']); ?>
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
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <p class="text-xs mt-1 font-medium truncate w-full text-center"><?php echo e($likedUser->name); ?></p>
                            <!--[if BLOCK]><![endif]--><?php if($likedData['hasMatched']): ?>
                                <a href="<?php echo e(route('caixa_de_mensagens')); ?>?user=<?php echo e($likedUser->id); ?>" class="text-xs text-green-500 hover:text-green-600">Mensagem</a>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Lista de usuários próximos -->
            <div id="nearby_user" class="p-6 rounded-xl shadow-md w-full bg-white dark:bg-gray-800 h-full"
                wire:key="nearby-users-<?php echo e(count($nearbyUsers)); ?>">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Usuários próximos:</h3>
                <span class="text-sm text-gray-500">Ordenados por distância</span>
            </div>

            <!--[if BLOCK]><![endif]--><?php if(count($nearbyUsers) > 0): ?>
                <ul class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $nearbyUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $user = $userData['user']; ?>
                        <li wire:key="nearby-user-<?php echo e($user->id); ?>" class="flex items-center p-3 rounded-lg shadow-md border <?php echo e($userData['hasMatched'] ? 'border-green-300 dark:border-green-700' : 'border-gray-200 dark:border-gray-700'); ?> hover:shadow-lg transition-shadow duration-200">
                            <div class="relative mr-3">
                                <!--[if BLOCK]><![endif]--><?php if($user->photos && $user->photos->count()): ?>
                                    <img src="<?php echo e(asset('storage/' . $user->photos->first()->photo_path)); ?>"
                                        alt="Foto de <?php echo e($user->name); ?>"
                                        class="h-14 w-14 rounded-full object-cover border-2 <?php echo e($userData['hasMatched'] ? 'border-green-400' : ($userData['hasLiked'] ? 'border-pink-400' : 'border-gray-300')); ?>">
                                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user-status-indicator', ['userId' => $user->id]);

$__html = app('livewire')->mount($__name, $__params, 'lw-434255698-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                <?php elseif($user->userPhoto): ?>
                                    <img src="<?php echo e(Storage::url($user->userPhoto)); ?>"
                                        alt="Foto de <?php echo e($user->name); ?>"
                                        class="h-14 w-14 rounded-full object-cover border-2 <?php echo e($userData['hasMatched'] ? 'border-green-400' : ($userData['hasLiked'] ? 'border-pink-400' : 'border-gray-300')); ?>">
                                <?php else: ?>
                                    <span class="flex h-14 w-14 rounded-full bg-gray-200 items-center justify-center text-2xl text-gray-400 border-2 <?php echo e($userData['hasMatched'] ? 'border-green-400' : ($userData['hasLiked'] ? 'border-pink-400' : 'border-gray-300')); ?>">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.42 0 4.675.573 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center">
                                    <h4 class="font-semibold text-gray-900 dark:text-white"><?php echo e($user->name); ?></h4>
                                    <!--[if BLOCK]><![endif]--><?php if($userData['hasMatched']): ?>
                                        <span class="ml-1 text-green-500">
                                            <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'check-badge','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'check-badge','class' => 'w-4 h-4']); ?>
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
                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <a href="<?php echo e($user->username); ?>" class="text-blue-500 hover:text-blue-600 text-sm"><?php echo e($user->username); ?></a>

                                <!-- Distância com ícone -->
                                <div class="flex items-center mt-1 text-gray-500">
                                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'map-pin','class' => 'w-4 h-4 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'map-pin','class' => 'w-4 h-4 mr-1']); ?>
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
                                    <p class="text-sm"><?php echo e(number_format($user->distance, 1)); ?> km</p>
                                </div>
                            </div>

                            <!--[if BLOCK]><![endif]--><?php if($userData['hasMatched']): ?>
                                <a
                                    href="<?php echo e(route('caixa_de_mensagens')); ?>?user=<?php echo e($user->id); ?>"
                                    class="ml-2 bg-green-500 hover:bg-green-600 text-white font-medium py-1 px-2 rounded-full flex items-center"
                                >
                                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'chat-bubble-left-right','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'chat-bubble-left-right','class' => 'w-4 h-4']); ?>
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
                                </a>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </ul>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'users','class' => 'w-12 h-12 mx-auto text-gray-400 mb-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'users','class' => 'w-12 h-12 mx-auto text-gray-400 mb-3']); ?>
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
                    <p>Nenhum usuário próximo encontrado dentro de <?php echo e($maxDistance); ?>km.</p>
                    <p class="text-sm mt-2">Tente aumentar a distância máxima.</p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        </div>
    </div>

    <!-- Modal de Match -->
    <!--[if BLOCK]><![endif]--><?php if(session()->has('match')): ?>
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
        >
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">
                <div class="relative">
                    <!-- Botão de fechar -->
                    <button
                        @click="show = false"
                        class="absolute top-0 right-0 text-gray-500 hover:text-gray-700"
                    >
                        <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'x-mark','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'x-mark','class' => 'w-6 h-6']); ?>
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
                    </button>

                    <!-- Conteúdo do match -->
                    <div class="py-6">
                        <!-- Ícone de coração -->
                        <div class="flex justify-center mb-4">
                            <div class="relative">
                                <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'heart','class' => 'w-24 h-24 text-pink-500 animate-pulse']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heart','class' => 'w-24 h-24 text-pink-500 animate-pulse']); ?>
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
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold text-pink-600 mb-2">É um Match!</h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            <?php echo e(is_array(session('match')) ? session('match')['message'] : session('match')); ?>

                        </p>

                        <!--[if BLOCK]><![endif]--><?php if(is_array(session('match')) && isset(session('match')['user'])): ?>
                            <!-- Foto do usuário -->
                            <div class="flex justify-center mb-4">
                                <?php $matchedUser = session('match')['user']; ?>
                                <!--[if BLOCK]><![endif]--><?php if($matchedUser->photos && $matchedUser->photos->count()): ?>
                                    <img
                                        src="<?php echo e(asset('storage/' . $matchedUser->photos->first()->photo_path)); ?>"
                                        alt="Foto de <?php echo e($matchedUser->name); ?>"
                                        class="h-24 w-24 rounded-full object-cover border-4 border-pink-400"
                                    >
                                <?php elseif($matchedUser->userPhoto): ?>
                                    <img
                                        src="<?php echo e(asset($matchedUser->userPhoto)); ?>"
                                        alt="Foto de <?php echo e($matchedUser->name); ?>"
                                        class="h-24 w-24 rounded-full object-cover border-4 border-pink-400"
                                    >
                                <?php else: ?>
                                    <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center border-4 border-pink-400">
                                        <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'user','class' => 'w-12 h-12 text-gray-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'user','class' => 'w-12 h-12 text-gray-400']); ?>
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
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Botões de ação -->
                        <div class="flex justify-center gap-4 mt-4">
                            <button
                                @click="show = false"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-full"
                            >
                                Continuar Explorando
                            </button>

                            <!--[if BLOCK]><![endif]--><?php if(is_array(session('match')) && isset(session('match')['user'])): ?>
                                <a
                                    href="<?php echo e(route('caixa_de_mensagens')); ?>?user=<?php echo e(session('match')['user']->id); ?>"
                                    class="bg-pink-500 hover:bg-pink-600 text-white font-medium py-2 px-4 rounded-full flex items-center"
                                >
                                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'chat-bubble-left-right','class' => 'w-5 h-5 mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'chat-bubble-left-right','class' => 'w-5 h-5 mr-1']); ?>
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
                                    Enviar Mensagem
                                </a>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/swipe-match.blade.php ENDPATH**/ ?>