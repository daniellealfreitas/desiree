<span 
    wire:poll.20s="refreshStatus"
    class="absolute left-1 top-1 transform translate-x-1/4 -translate-y-1/4 w-3 h-3 rounded-full border-1 border-white 
        <?php if($status=='online'): ?> bg-green-500 
        <?php elseif($status=='away'): ?> bg-yellow-400 
        <?php else: ?> bg-red-500 
        <?php endif; ?>
        shadow"
    title="<?php echo e(ucfirst($status)); ?>"
    aria-label="status do usuário"
></span><?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/user-status-indicator.blade.php ENDPATH**/ ?>