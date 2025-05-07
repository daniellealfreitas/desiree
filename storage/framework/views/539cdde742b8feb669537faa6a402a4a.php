<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Meus Pagamentos')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Meus Pagamentos'))]); ?>
    <div class="flex flex-col">
        <div class=" overflow-x-auto pb-4">
            <div class="min-w-full inline-block align-middle">
                <div class="rounded-lg bg-zinc-800">
                    <table class="table-auto min-w-full rounded-xl">
                        <thead>
                            <tr class="bg-zinc-800 text-gray-50">
                                <th class="bg-zinc-800">
                                    <div class="flex items-center py-5 px-5 ">
                                        <input type="checkbox" id="select-all" class="w-5 h-5 appearance-none border border-gray-300 rounded-md mr-2 hover:border-indigo-500 hover:bg-indigo-100 checked:bg-no-repeat checked:bg-center checked:border-indigo-500 checked:bg-indigo-100" onclick="toggleSelectAll(this)">
                                        <script>
                                            function toggleSelectAll(selectAllCheckbox) {
                                                const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                                                checkboxes.forEach(checkbox => {
                                                    checkbox.checked = selectAllCheckbox.checked;
                                                });
                                            }
                                        </script>
                                    </div>
                                </th>
                                <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 font-semibold text-gray-300 capitalize"> ID </th>
                                <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 font-semibold text-gray-300 capitalize min-w-[150px]"> Nome &amp; Email </th>
                                <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 font-semibold text-gray-300 capitalize">Data do pagamento </th>
                                <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 font-semibold text-gray-300 capitalize"> Valor Pago </th>
                                <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 font-semibold text-gray-300 capitalize"> Status </th>
                                <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 font-semibold text-gray-300 capitalize"> Ações </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300">
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr id="table_row" class="<?php echo e($index % 2 === 0 ? 'bg-zinc-700' : 'bg-zinc-500'); ?> transition-all duration-500 hover:bg-zinc-400">
                                <td>
                                    <div class="flex items-center py-5 px-5">
                                        <input type="checkbox" value="" class="w-5 h-5 appearance-none border border-gray-300 rounded-md mr-2 hover:border-indigo-500 hover:bg-indigo-100 checked:bg-no-repeat checked:bg-center checked:border-indigo-500 checked:bg-indigo-100">
                                    </div>
                                </td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-300"><?php echo e($payment->user_id); ?></td>
                                <td class="px-5 py-3">
                                    <div class="w-48 flex items-center gap-3">
                                        <img src="<?php echo e($payment->user->userPhoto->photo_path); ?>" alt="<?php echo e($payment->user->name); ?> image" class="w-10 h-10 rounded-full object-cover">
                                        <div class="data">
                                            <p class="font-normal text-sm text-gray-300"><?php echo e($payment->user->name); ?></p>
                                            <p class="font-normal text-xs leading-5 text-gray-400"><?php echo e($payment->user->email); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-300"><?php echo e(\Carbon\Carbon::parse($payment->payment_date)->translatedFormat('d/F/Y')); ?></td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-300">R$<?php echo e($payment->amount); ?></td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-300">
                                    <div class="py-1.5 px-2.5 <?php echo e($payment->status === 1 ? 'bg-emerald-50' : 'bg-red-50'); ?> rounded-full flex justify-center w-20 items-center gap-1">
                                        <svg width="5" height="6" viewBox="0 0 5 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="2.5" cy="3" r="2.5" fill="<?php echo e($payment->status === 1 ? '#059669' : '#DC2626'); ?>"></circle>
                                        </svg>
                                        <span class="font-medium text-xs <?php echo e($payment->status === 1 ? 'text-emerald-600' : 'text-red-600'); ?>"><?php echo e($payment->status === 1 ? 'Ativo' : 'Inativo'); ?></span>
                                    </div>
                                </td>
                                <td class="flex p-5 items-center gap-0.5">
                                    <button class="p-2 rounded-full bg-white group transition-all duration-500 hover:bg-black flex item-center">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="stroke-black group-hover:stroke-white" d="M10.0161 14.9897V15.0397M10.0161 9.97598V10.026M10.0161 4.96231V5.01231" stroke="black" stroke-width="2.5" stroke-linecap="round"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\desiree2\resources\views/meus-pagamentos.blade.php ENDPATH**/ ?>