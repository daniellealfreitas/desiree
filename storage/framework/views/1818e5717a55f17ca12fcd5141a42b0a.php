<div>
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden">
        <!-- Cabeçalho com estatísticas -->
        <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold mb-4">Histórico de Pontuação</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400"><?php echo e(number_format($totalPoints)); ?></div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                </div>
                
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400"><?php echo e(number_format($dailyPoints)); ?></div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Hoje</div>
                </div>
                
                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400"><?php echo e(number_format($weeklyPoints)); ?></div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Semana</div>
                </div>
                
                <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-orange-600 dark:text-orange-400"><?php echo e(number_format($monthlyPoints)); ?></div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Mês</div>
                </div>
                
                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">#<?php echo e($rankingPosition); ?></div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Ranking</div>
                </div>
            </div>
            
            <!-- Sequência de dias -->
            <div class="flex items-center mb-4">
                <div class="mr-2">
                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'calendar','class' => 'w-5 h-5 text-orange-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'calendar','class' => 'w-5 h-5 text-orange-500']); ?>
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
                <div>
                    <span class="font-semibold"><?php echo e($streakDays); ?> <?php echo e($streakDays == 1 ? 'dia' : 'dias'); ?></span> 
                    <span class="text-gray-600 dark:text-gray-400">consecutivos de atividade</span>
                </div>
            </div>
            
            <!-- Filtros -->
            <div class="flex flex-wrap gap-2 mt-4">
                <div class="mr-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Período:</label>
                    <div class="flex space-x-2">
                        <button wire:click="setPeriod('all')" class="px-3 py-1 text-sm rounded-full <?php echo e($period === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'); ?>">
                            Todos
                        </button>
                        <button wire:click="setPeriod('1 day')" class="px-3 py-1 text-sm rounded-full <?php echo e($period === '1 day' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'); ?>">
                            Hoje
                        </button>
                        <button wire:click="setPeriod('1 week')" class="px-3 py-1 text-sm rounded-full <?php echo e($period === '1 week' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'); ?>">
                            Semana
                        </button>
                        <button wire:click="setPeriod('1 month')" class="px-3 py-1 text-sm rounded-full <?php echo e($period === '1 month' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'); ?>">
                            Mês
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo:</label>
                    <div class="flex space-x-2">
                        <button wire:click="setActionType('all')" class="px-3 py-1 text-sm rounded-full <?php echo e($actionType === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'); ?>">
                            Todos
                        </button>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $actionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button wire:click="setActionType('<?php echo e($type); ?>')" class="px-3 py-1 text-sm rounded-full <?php echo e($actionType === $type ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'); ?>">
                                <?php echo e(ucfirst($type)); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lista de atividades -->
        <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-<?php echo e($log->color); ?>-100 dark:bg-<?php echo e($log->color); ?>-900/30">
                            <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => ''.e($log->icon).'','class' => 'w-5 h-5 text-'.e($log->color).'-600 dark:text-'.e($log->color).'-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => ''.e($log->icon).'','class' => 'w-5 h-5 text-'.e($log->color).'-600 dark:text-'.e($log->color).'-400']); ?>
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
                    
                    <div class="flex-grow">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium"><?php echo e($log->formatted_description); ?></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($log->time_ago); ?></p>
                            </div>
                            
                            <div class="text-lg font-bold <?php echo e($log->points >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'); ?>">
                                <?php echo e($log->points >= 0 ? '+' : ''); ?><?php echo e($log->points); ?>

                            </div>
                        </div>
                        
                        <div class="mt-2 flex justify-between items-center">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Total: <?php echo e(number_format($log->total_points)); ?> pontos
                            </div>
                            
                            <!--[if BLOCK]><![endif]--><?php if($log->ranking_position): ?>
                                <div class="text-sm bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">
                                    Posição #<?php echo e($log->ranking_position); ?>

                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    Nenhum registro de pontuação encontrado.
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        
        <!-- Paginação -->
        <div class="p-4 border-t border-neutral-200 dark:border-neutral-700">
            <?php echo e($logs->links()); ?>

        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/user-points-history.blade.php ENDPATH**/ ?>