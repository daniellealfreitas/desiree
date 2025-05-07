<div class="container mx-auto p-4">
    <div class="flex flex-col md:flex-row gap-4 h-[calc(100vh-12rem)]">
        <!-- Sidebar with conversations -->
        <div class="w-full md:w-1/3 bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-zinc-700 flex justify-between items-center">
                <h2 class="text-xl font-semibold">Conversas</h2>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live="searchTerm"
                        placeholder="Buscar usuários..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-zinc-700"
                    >
                </div>
            </div>

            <div class="overflow-y-auto flex-grow">
                <!--[if BLOCK]><![endif]--><?php if($searchTerm): ?>
                    <!-- Search results -->
                    <div class="p-2">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Resultados da busca</h3>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div
                                wire:click="startNewConversation(<?php echo e($user->id); ?>)"
                                class="flex items-center p-3 hover:bg-gray-100 dark:hover:bg-zinc-700 rounded-lg cursor-pointer"
                            >
                                <div class="relative">
                                    <img
                                        src="<?php echo e($user->userPhotos->first() ? asset($user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg')); ?>"
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                    <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full <?php echo e($user->presence_status === 'online' ? 'bg-green-500' : ($user->presence_status === 'away' ? 'bg-yellow-500' : 'bg-gray-500')); ?>"></div>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium"><?php echo e($user->name); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->username }}</p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-center text-gray-500 dark:text-gray-400 py-4">Nenhum usuário encontrado</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php else: ?>
                    <!-- Conversation list -->
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div
                            wire:click="selectConversation(<?php echo e($conversation['user']->id); ?>)"
                            class="flex items-center p-3 hover:bg-gray-100 dark:hover:bg-zinc-700 cursor-pointer <?php echo e($selectedConversation == $conversation['user']->id ? 'bg-purple-50 dark:bg-zinc-700' : ''); ?>"
                        >
                            <div class="relative">
                                <img
                                    src="<?php echo e($conversation['user']->userPhotos->first() ? asset($conversation['user']->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg')); ?>"
                                    class="w-10 h-10 rounded-full object-cover"
                                >
                                <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full <?php echo e($conversation['user']->presence_status === 'online' ? 'bg-green-500' : ($conversation['user']->presence_status === 'away' ? 'bg-yellow-500' : 'bg-gray-500')); ?>"></div>
                                <!--[if BLOCK]><![endif]--><?php if($conversation['unread_count'] > 0): ?>
                                    <div class="absolute -top-1 -right-1 bg-purple-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        <?php echo e($conversation['unread_count']); ?>

                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div class="ml-3 flex-grow">
                                <div class="flex justify-between items-center">
                                    <p class="font-medium"><?php echo e($conversation['user']->name); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <?php echo e($conversation['latest_message']->created_at->diffForHumans(null, true)); ?>

                                    </p>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate w-40">
                                    <?php echo e($conversation['latest_message']->body); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                            <p>Nenhuma conversa encontrada</p>
                            <p class="text-sm mt-2">Use a busca para iniciar uma conversa</p>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <!-- Message area -->
        <div class="w-full md:w-2/3 bg-white dark:bg-zinc-800 rounded-lg shadow-md overflow-hidden flex flex-col">
            <!--[if BLOCK]><![endif]--><?php if($selectedConversation): ?>
                <?php
                    $selectedUser = $users->firstWhere('id', $selectedConversation) ??
                        collect($conversations)->firstWhere('user.id', $selectedConversation)['user'] ?? null;
                ?>

                <!--[if BLOCK]><![endif]--><?php if($selectedUser): ?>
                    <!-- Conversation header -->
                    <div class="p-4 border-b border-gray-200 dark:border-zinc-700 flex items-center">
                        <div class="relative">
                            <img
                                src="<?php echo e($selectedUser->userPhotos->first() ? asset($selectedUser->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg')); ?>"
                                class="w-10 h-10 rounded-full object-cover"
                            >
                            <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full <?php echo e($selectedUser->presence_status === 'online' ? 'bg-green-500' : ($selectedUser->presence_status === 'away' ? 'bg-yellow-500' : 'bg-gray-500')); ?>"></div>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium"><?php echo e($selectedUser->name); ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <?php echo e($selectedUser->presence_status === 'online' ? 'Online' : ($selectedUser->presence_status === 'away' ? 'Ausente' : 'Offline')); ?>

                            </p>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="flex-grow overflow-y-auto p-4 space-y-4" id="message-container">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex <?php echo e($message->sender_id == Auth::id() ? 'justify-end' : 'justify-start'); ?>">
                                <div class="<?php echo e($message->sender_id == Auth::id() ? 'bg-purple-500 text-white' : 'bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-white'); ?> rounded-lg p-3 max-w-xs md:max-w-md">
                                    <p><?php echo e($message->body); ?></p>
                                    <p class="text-xs <?php echo e($message->sender_id == Auth::id() ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400'); ?> text-right mt-1">
                                        <?php echo e($message->created_at->format('H:i')); ?>

                                    </p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Message input -->
                    <div class="p-4 border-t border-gray-200 dark:border-zinc-700">
                        <form wire:submit.prevent="sendMessage" class="flex items-center">
                            <input
                                type="text"
                                wire:model="messageBody"
                                placeholder="Digite sua mensagem..."
                                class="flex-grow px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-zinc-700"
                            >
                            <button
                                type="submit"
                                class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-r-lg"
                            >
                                <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'paper-airplane','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'paper-airplane','class' => 'w-5 h-5']); ?>
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
                        </form>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php else: ?>
                <div class="flex-grow flex items-center justify-center">
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'chat-bubble-left-right','class' => 'w-16 h-16 mx-auto mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chat-bubble-left-right','class' => 'w-16 h-16 mx-auto mb-4']); ?>
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
                        <h3 class="text-xl font-medium mb-2">Suas mensagens</h3>
                        <p>Selecione uma conversa ou inicie uma nova</p>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Scroll to bottom of messages when conversation changes or new message is sent
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('message.processed', (message, component) => {
            const container = document.getElementById('message-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/messages.blade.php ENDPATH**/ ?>