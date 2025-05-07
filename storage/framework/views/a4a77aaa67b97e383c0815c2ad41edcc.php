<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
    <!-- Cabeçalho do calendário -->
    <div class="p-4 bg-indigo-600 text-white flex items-center justify-between">
        <button wire:click="previousMonth" class="p-2 rounded-full hover:bg-indigo-700 transition-colors">
            <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'chevron-left','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'chevron-left','class' => 'w-5 h-5']); ?>
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
        
        <h2 class="text-xl font-bold">
            <?php
                $monthNames = [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                ];
            ?>
            <?php echo e($monthNames[$currentMonth]); ?> <?php echo e($currentYear); ?>

        </h2>
        
        <button wire:click="nextMonth" class="p-2 rounded-full hover:bg-indigo-700 transition-colors">
            <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['icon' => 'chevron-right','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'chevron-right','class' => 'w-5 h-5']); ?>
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
    </div>
    
    <!-- Dias da semana -->
    <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800">Seg</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800">Ter</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 bg-purple-50 dark:bg-purple-900/20">Qua</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800">Qui</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 bg-pink-50 dark:bg-pink-900/20">Sex</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 bg-blue-50 dark:bg-blue-900/20">Sáb</div>
        <div class="p-2 text-center font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800">Dom</div>
    </div>
    
    <!-- Dias do mês -->
    <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $weeks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $week; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $hasEvents = isset($monthEvents[$day['date']]) && count($monthEvents[$day['date']]) > 0;
                    $isSelected = $selectedDate === $day['date'];
                    
                    // Definir classes base
                    $dayClasses = 'min-h-[100px] p-2 bg-white dark:bg-gray-800 flex flex-col';
                    
                    // Adicionar classes para dias especiais
                    if ($day['isWednesday']) {
                        $dayClasses .= ' bg-purple-50 dark:bg-purple-900/20';
                    } elseif ($day['isFriday']) {
                        $dayClasses .= ' bg-pink-50 dark:bg-pink-900/20';
                    } elseif ($day['isSaturday']) {
                        $dayClasses .= ' bg-blue-50 dark:bg-blue-900/20';
                    }
                    
                    // Adicionar classes para dias de outros meses
                    if (!$day['isCurrentMonth']) {
                        $dayClasses .= ' opacity-50';
                    }
                    
                    // Adicionar classes para o dia atual
                    if ($day['isToday']) {
                        $dayClasses .= ' border-2 border-indigo-500';
                    }
                    
                    // Adicionar classes para o dia selecionado
                    if ($isSelected) {
                        $dayClasses .= ' bg-indigo-50 dark:bg-indigo-900/20';
                    }
                ?>
                
                <div 
                    wire:click="selectDate('<?php echo e($day['date']); ?>')" 
                    class="<?php echo e($dayClasses); ?>"
                >
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium <?php echo e($day['isToday'] ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300'); ?>">
                            <?php echo e($day['day']); ?>

                        </span>
                        
                        <!--[if BLOCK]><![endif]--><?php if($hasEvents): ?>
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-xs text-white">
                                <?php echo e(count($monthEvents[$day['date']])); ?>

                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    
                    <!--[if BLOCK]><![endif]--><?php if($hasEvents): ?>
                        <div class="space-y-1 overflow-y-auto flex-1">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $monthEvents[$day['date']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="text-xs p-1 rounded bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-200 truncate">
                                    <?php echo e($event->formatted_start_time); ?> - <?php echo e($event->name); ?>

                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    
    <!-- Eventos do dia selecionado -->
    <!--[if BLOCK]><![endif]--><?php if($selectedDate && count($selectedDateEvents) > 0): ?>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Eventos em <?php echo e(\Carbon\Carbon::parse($selectedDate)->format('d/m/Y')); ?>

            </h3>
            
            <div class="space-y-4">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $selectedDateEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <div class="flex-shrink-0 h-12 w-12 mr-4">
                            <img src="<?php echo e($event->image_url); ?>" alt="<?php echo e($event->name); ?>" class="h-12 w-12 rounded-lg object-cover">
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                <?php echo e($event->name); ?>

                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <?php echo e($event->formatted_start_time); ?> - <?php echo e($event->formatted_end_time); ?>

                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <?php echo e($event->location); ?>

                            </p>
                        </div>
                        
                        <div class="ml-4">
                            <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['href' => ''.e(route('events.show', $event->slug)).'','color' => 'primary','size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('events.show', $event->slug)).'','color' => 'primary','size' => 'xs']); ?>
                                Ver Detalhes
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/events/event-calendar.blade.php ENDPATH**/ ?>