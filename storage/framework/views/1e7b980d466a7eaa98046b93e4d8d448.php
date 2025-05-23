<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<title><?php echo e($title ?? 'Desiree Swing Club - Curitiba'); ?></title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

<!-- Cropper.js CSS -->
<link href="<?php echo e(asset('css/cropper.css')); ?>" rel="stylesheet" />


<!-- Livewire Styles -->
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


<!-- Scripts personalizados -->
<?php if(auth()->guard()->check()): ?>
<!-- Script de geolocalização automática -->
<script src="<?php echo e(asset('js/auto-geolocation.js')); ?>"></script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/partials/head.blade.php ENDPATH**/ ?>