<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Renovar VIP')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Renovar VIP'))]); ?>


      <div class="container px-6 py-8 mx-auto">
            <!-- Flash Messages -->
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
                <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                    <?php echo e(session('info')); ?>

                </div>
            <?php endif; ?>
            <!-- component -->




            <div class="grid gap-6 mt-16 -mx-6 sm:gap-8 sm:grid-cols-2 lg:grid-cols-5 xl:grid-cols-5">
                  <div class="px-6 py-4 bg-clip-padding backdrop-filter backdrop-blur-xl bg-opacity-60 border border-opacity-20 duration-200 transform rounded-lg">
                        <p class="text-lg font-medium text-white dark:text-gray-100">30 Dias</p>
                        <h4 class="mt-2 text-4xl font-semibold text-white dark:text-gray-100">R$48
                              </h4>
                        <p class="mt-4 text-white dark:text-gray-300">Pagamento por 30 dias de uso</p>

                        <div class="mt-8 space-y-8">
                              <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500"
                                          viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                    </svg>

                                    <span class="mx-4 text-gray-400 dark:text-gray-300">Unlimited users</span>
                              </div>
                        </div>

                        <form action="<?php echo e(route('vip.checkout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="plan" value="30">
                            <input type="hidden" name="price" value="48">
                            <button type="submit" class="w-full px-4 py-2 mt-10 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Pagar</button>
                        </form>
                  </div>
                   <div class="px-6 py-4 bg-clip-padding backdrop-filter backdrop-blur-xl bg-opacity-60 border border-opacity-20 duration-200 transform rounded-lg">
                        <p class="text-lg font-medium text-white dark:text-gray-100">60 Dias</p>
                        <h4 class="mt-2 text-4xl font-semibold text-white dark:text-gray-100">R$88
                              </h4>
                        <p class="mt-4 text-white dark:text-gray-300">Pagamento por 60 dias de uso</p>

                        <div class="mt-8 space-y-8">
                              <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500"
                                          viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                    </svg>

                                    <span class="mx-4 text-gray-400 dark:text-gray-300">Unlimited users</span>
                              </div>
                        </div>

                        <form action="<?php echo e(route('vip.checkout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="plan" value="60">
                            <input type="hidden" name="price" value="88">
                            <button type="submit" class="w-full px-4 py-2 mt-10 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Pagar</button>
                        </form>
                  </div>
                   <div class="px-6 py-4 bg-clip-padding backdrop-filter backdrop-blur-xl bg-opacity-60 border border-opacity-20 duration-200 transform rounded-lg">
                        <p class="text-lg font-medium text-white dark:text-gray-100">90 Dias</p>
                        <h4 class="mt-2 text-4xl font-semibold text-white dark:text-gray-100">R$130
                              </h4>
                        <p class="mt-4 text-white dark:text-gray-300">Pagamento por 90 dias de uso</p>

                        <div class="mt-8 space-y-8">
                              <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500"
                                          viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                    </svg>

                                    <span class="mx-4 text-gray-400 dark:text-gray-300">Unlimited users</span>
                              </div>
                        </div>

                        <form action="<?php echo e(route('vip.checkout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="plan" value="90">
                            <input type="hidden" name="price" value="130">
                            <button type="submit" class="w-full px-4 py-2 mt-10 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Pagar</button>
                        </form>
                  </div>
                   <div class="px-6 py-4 bg-clip-padding backdrop-filter backdrop-blur-xl bg-opacity-60 border border-opacity-20 duration-200 transform rounded-lg">
                        <p class="text-lg font-medium text-white dark:text-gray-100">180 Dias</p>
                        <h4 class="mt-2 text-4xl font-semibold text-white dark:text-gray-100">R$254
                              </h4>
                        <p class="mt-4 text-white dark:text-gray-300">Pagamento por 180 dias de uso</p>

                        <div class="mt-8 space-y-8">
                              <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500"
                                          viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                    </svg>

                                    <span class="mx-4 text-gray-400 dark:text-gray-300">Unlimited users</span>
                              </div>
                        </div>

                        <form action="<?php echo e(route('vip.checkout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="plan" value="180">
                            <input type="hidden" name="price" value="254">
                            <button type="submit" class="w-full px-4 py-2 mt-10 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Pagar</button>
                        </form>
                  </div>
                   <div class="px-6 py-4 bg-clip-padding backdrop-filter backdrop-blur-xl bg-opacity-60 border border-opacity-20 duration-200 transform rounded-lg">
                        <p class="text-lg font-medium text-white dark:text-gray-100">360 Dias</p>
                        <h4 class="mt-2 text-4xl font-semibold text-white dark:text-gray-100">R$525
                              </h4>
                        <p class="mt-4 text-white dark:text-gray-300">Pagamento por 360 dias de uso</p>

                        <div class="mt-8 space-y-8">
                              <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500"
                                          viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                    </svg>

                                    <span class="mx-4 text-gray-400 dark:text-gray-300">Unlimited users</span>
                              </div>
                        </div>

                        <form action="<?php echo e(route('vip.checkout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="plan" value="360">
                            <input type="hidden" name="price" value="525">
                            <button type="submit" class="w-full px-4 py-2 mt-10 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Pagar</button>
                        </form>
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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\desiree2\resources\views/renovar-vip.blade.php ENDPATH**/ ?>