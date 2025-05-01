<div>
    

        <div class="mt-4 border rounded p-4">
            <div class="mb-4">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-2">
                        <strong><?php echo e($message->sender->name); ?>:</strong> <?php echo e($message->body); ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="flex items-center">
                <input wire:model="newMessage" type="text" placeholder="Type a message..." class="border p-2 rounded flex-grow">
                <button wire:click="sendMessage" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded">Send</button>
            </div>
        </div>
 
</div><?php /**PATH C:\xampp\htdocs\desiree2\resources\views\livewire/mensagens.blade.php ENDPATH**/ ?>