<?php

use App\Models\User;
use App\Models\Post;
use App\Models\UserPhoto;
use App\Models\UserCoverPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;

?>

<div class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
    <div class="relative h-32 bg-cover bg-center rounded-t-lg" 
        style="background:url(<?php echo e($this->cover() ?? asset('images/users/capa.jpg')); ?>); background-size: cover; background-position: center;">
    </div>
    <div class="relative z-10 -mt-12 flex flex-col items-center">
        <img src="<?php echo e($this->avatar() ?? asset('images/users/avatar.jpg')); ?>" 
            alt="Foto de Perfil" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
        <h2 class="text-xl font-semibold mt-2"><?php echo e($user->name); ?></h2>
        <p class="text-gray-600"><?php echo e('@' . $user->username); ?></p>
        <div class="mt-4 flex justify-around w-full">
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