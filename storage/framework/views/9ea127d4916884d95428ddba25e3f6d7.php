<?php

use function Livewire\Volt\{state, computed, mount};
use App\Models\FollowRequest;
use App\Models\Notification;
use Carbon\Carbon;

state(['followRequests' => fn() => 
    FollowRequest::where('receiver_id', auth()->id())
        ->where('status', 'pending')
        ->with(['sender.userPhotos' => function($query) {
            $query->latest()->take(1);
        }])
        ->latest()
        ->get()
]);

mount(function() {
    $this->loadFollowRequests();
});

function loadFollowRequests() {
    $this->followRequests = FollowRequest::where('receiver_id', auth()->id())
        ->where('status', 'pending')
        ->with(['sender.userPhotos' => function($query) {
            $query->latest()->take(1);
        }])
        ->latest()
        ->get();
}

function hasRequests() {
    return $this->followRequests->count() > 0;
}

function accept($requestId) {
    try {
        $request = FollowRequest::findOrFail($requestId);
        
        // Check if relationship already exists
        if (!auth()->user()->followers()->where('follower_id', $request->sender_id)->exists()) {
            auth()->user()->followers()->attach($request->sender_id);
        }
        
        $request->update(['status' => 'accepted']);
        
        Notification::create([
            'user_id' => $request->sender_id,
            'sender_id' => auth()->id(),
            'type' => 'follow_accepted',
            'message' => auth()->user()->username . ' aceitou sua solicitação para seguir'
        ]);

        $this->dispatch('notification-received');
        $this->loadFollowRequests();
    } catch (\Exception $e) {
        logger()->error('Follow accept error: ' . $e->getMessage());
    }
}

function reject($requestId) {
    $request = FollowRequest::findOrFail($requestId);
    $request->update(['status' => 'rejected']);
    
    Notification::create([
        'user_id' => $request->sender_id,
        'sender_id' => auth()->id(),
        'type' => 'follow_rejected',
        'message' => auth()->user()->username . ' rejeitou sua solicitação para seguir'
    ]);

    $this->dispatch('notification-received');
    $this->loadFollowRequests();
}

?>

<div class="relative">
    <?php if (isset($component)) { $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::dropdown','data' => ['class' => 'max-lg:hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'max-lg:hidden']); ?>
        <?php if (isset($component)) { $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navbar.item','data' => ['icon' => 'user-plus','badge' => count($followRequests)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navbar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'user-plus','badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(count($followRequests))]); ?>
            Solicitações
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $attributes = $__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__attributesOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48)): ?>
<?php $component = $__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48; ?>
<?php unset($__componentOriginalc4cbba45ed073bedf6d5fbbd59b58e48); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal0acbef9d8e9b45c80d953734a49636ad = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0acbef9d8e9b45c80d953734a49636ad = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navmenu.index','data' => ['class' => 'w-80']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navmenu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-80']); ?>
            <!--[if BLOCK]><![endif]--><?php if(count($followRequests) > 0): ?>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $followRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-3 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <div class="flex items-center gap-2 mb-2">
                            <img src="<?php echo e(!empty($request->sender->userPhotos->first()) ? Storage::url($request->sender->userPhotos->first()->photo_path) : asset('images/users/default.jpg')); ?>" 
                                 class="w-8 h-8 rounded-full object-cover">
                            <div class="flex-1">
                                <p class="text-sm">
                                    <a href="/<?php echo e($request->sender->username); ?>" class="font-semibold hover:underline">
                                        <?php echo e($request->sender->username); ?>

                                    </a>
                                    quer seguir você
                                </p>
                                <p class="text-xs text-gray-500">
                                    <?php echo e(Carbon::parse($request->created_at)->diffForHumans()); ?>

                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2 justify-end">
                            <button wire:click="accept('<?php echo e($request->id); ?>')"
                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded-full hover:bg-blue-600">
                                Aceitar
                            </button>
                            <button wire:click="reject('<?php echo e($request->id); ?>')"
                                    class="px-3 py-1 bg-gray-500 text-white text-sm rounded-full hover:bg-gray-600">
                                Rejeitar
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            <?php else: ?>
                <div class="p-3">
                    <p class="text-sm text-gray-500">Nenhuma solicitação</p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0acbef9d8e9b45c80d953734a49636ad)): ?>
<?php $attributes = $__attributesOriginal0acbef9d8e9b45c80d953734a49636ad; ?>
<?php unset($__attributesOriginal0acbef9d8e9b45c80d953734a49636ad); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0acbef9d8e9b45c80d953734a49636ad)): ?>
<?php $component = $__componentOriginal0acbef9d8e9b45c80d953734a49636ad; ?>
<?php unset($__componentOriginal0acbef9d8e9b45c80d953734a49636ad); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $attributes = $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $component = $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/livewire/follow-request-notifications.blade.php ENDPATH**/ ?>