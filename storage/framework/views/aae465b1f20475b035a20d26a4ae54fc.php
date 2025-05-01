<div class="flex flex-col w-full" x-data="{ anim: false }">
    <!-- Primeira row: Usuário atual ou mensagem -->
    <div class="w-full mt-10">
    <!--[if BLOCK]><![endif]--><?php if($currentUser): ?>
        <div id="user_match" class="p-6 rounded-xl shadow-md w-full text-center" x-show="!anim" x-transition>
            
            <!--[if BLOCK]><![endif]--><?php if($currentUser->photos && $currentUser->photos->count()): ?>
                <div x-data="{ idx: 0, total: <?php echo e($currentUser->photos->count()); ?> }" 
                    class="mb-4 flex flex-col items-center justify-center" >
                    <div class="relative">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currentUser->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <img 
                                x-show="idx === <?php echo e($loop->index); ?>" 
                                src="<?php echo e(asset('storage/' . $photo->photo_path)); ?>" 
                                alt="Foto de <?php echo e($currentUser->name); ?>" 
                                class="h-44 w-44 rounded-full object-cover border-2 border-pink-400 mx-auto"
                                loading="lazy"
                                style="display: none;"
                                x-bind:style="idx === <?php echo e($loop->index); ?> ? 'display:block' : 'display:none'"
                            >
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($currentUser->photos->count() > 1): ?>
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
                        <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < $currentUser->photos->count(); $i++): ?>
                            <span 
                                :class="{'bg-pink-400': idx === <?php echo e($i); ?>, 'bg-gray-300': idx !== <?php echo e($i); ?>}"
                                class="w-2 h-2 rounded-full block"
                            ></span>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            <?php elseif($currentUser->userPhoto): ?>
                <img src="<?php echo e(asset( $currentUser->userPhoto)); ?>"
                     alt="Foto de <?php echo e($currentUser->name); ?>"
                     class="h-54 w-54 rounded-full object-cover mx-auto mb-4 border-2 border-pink-400">
            <?php else: ?>
                <span class="inline-block h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center text-4xl text-gray-400 mb-4 mx-auto border-2 border-gray-200">
                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.42 0 4.675.573 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <h2 class="text-xl font-bold"><?php echo e($currentUser->name); ?></h2>
            <a href="<?php echo e($currentUser->username); ?>" class="text-blue-500 hover:text-blue-600"><?php echo e($currentUser->username); ?></a>
            <p class="text-xs text-gray-400">Local aproximado: <?php echo e(number_format($currentUser->distance, 1)); ?> km</p>

            <div class="flex justify-center-safe mt-6 gap-2">
                <button wire:click="pass" @click="anim = true; setTimeout(() => anim = false, 300)" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded-full"
                >
                    ❌ Pass
                </button>
                <button
                    wire:click="like"
                    @click="anim = true; setTimeout(() => anim = false, 300)"
                    class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-full"
                >
                    ❤️ Like
                </button>
            </div>
        </div>

    <?php else: ?>
        <p class="text-gray-500 text-center mt-10">Você já viu todo mundo por perto 🧭</p>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Segunda row: Lista de usuários próximos -->
    <div class="w-full">
       <div id="nearby_user" class="mt-8 p-6 rounded-xl shadow-md w-full">
    <h3 class="text-lg font-bold mb-4">Usuários próximos:</h3>
    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-4 gap-y-4">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="flex flex-col items-center justify-between p-4 rounded-lg shadow-md border border-zinc-600">
                <div class="flex flex-col items-center space-y-3">
                    <!--[if BLOCK]><![endif]--><?php if($user->photos && $user->photos->count()): ?>
                        <img src="<?php echo e(asset('storage/' . $user->photos->first()->photo_path)); ?>"
                             alt="Foto de <?php echo e($user->name); ?>"
                             class="h-12 w-12 rounded-full object-cover border-2 border-pink-300">
                    <?php elseif($user->userPhoto): ?>
                        <img src="<?php echo e(Storage::url($user->userPhoto)); ?>"
                             alt="Foto de <?php echo e($user->name); ?>"
                             class="h-12 w-12 rounded-full object-cover border-2 border-pink-300">
                    <?php else: ?>
                        <span class="inline-block h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-2xl text-gray-400 border-2 border-gray-200">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.42 0 4.675.573 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <div class="text-center">
                        <h4 class="font-semibold"><?php echo e($user->name); ?></h4>
                        <a href="<?php echo e($user->username); ?>" class="text-blue-500 hover:text-blue-600"><?php echo e($user->username); ?></a>
                        <p class="text-sm text-gray-500"><?php echo e(number_format($user->distance, 1)); ?> km</p>
                    </div>
                </div>
                <div class="flex items-center justify-center mt-2">
                    <button
                        wire:click="like(<?php echo e($user->id); ?>)"
                        class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-full"
                    >
                        ❤️
                    </button>
                    <button
                        wire:click="pass(<?php echo e($user->id); ?>)"
                        class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded-full ml-2"
                    >
                        ❌
                    </button>
                </div>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </ul>
</div>

    </div>

    <!--[if BLOCK]><![endif]--><?php if(session()->has('match')): ?>
        <div class="fixed bottom-10 bg-green-200 p-4 rounded-xl shadow-xl">
            <?php echo e(session('match')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/swipe-match.blade.php ENDPATH**/ ?>