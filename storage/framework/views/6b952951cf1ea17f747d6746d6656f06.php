<?php

use App\Models\User;
use App\Models\Post;
use App\Models\UserPhoto;
use App\Models\UserCoverPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;

?>

<div id="Container" class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
    <div id="capa" class="relative h-32 bg-cover bg-center rounded-t-lg" 
        style="background:url(<?php echo e($this->cover() ?? asset('images/users/capa.jpg')); ?>); background-size: cover; background-position: center;">
    </div>
    <div id="container_user"  class="relative z-10 -mt-12 flex flex-col items-center">
        <div id="avatar"   class="relative">
            <img src="<?php echo e($this->avatar() ?? asset('images/users/avatar.jpg')); ?>" 
            alt="Foto de Perfil" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user-status-indicator', ['userId' => $user->id]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3132980349-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>
        <h2 class="text-xl font-semibold mt-2"><?php echo e($user->name); ?></h2>
        <p class="text-gray-600">
            <a href="<?php echo e(route('user.profile', ['username' => $user->username])); ?>" class="hover:underline">
                <?php echo e('@' . $user->username); ?>

            </a>
        </p>
        <div id="info_user" class="mt-4 flex justify-around w-full">
            <div class="text-center">
                <p class="text-lg font-semibold"><?php echo e($this->postsCount()); ?></p>
                <p class="text-gray-500">Posts</p>
            </div>
            <div class="text-center">
                <p class="text-lg font-semibold"><?php echo e($this->followingCount()); ?></p>
                <p class="text-gray-500">Seguindo</p>
            </div>
            <div class="text-center">
                <p class="text-lg font-semibold"><?php echo e($this->followersCount()); ?></p>
                <p class="text-gray-500">Seguidores</p>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\desiree2\resources\views\livewire/user-profile.blade.php ENDPATH**/ ?>