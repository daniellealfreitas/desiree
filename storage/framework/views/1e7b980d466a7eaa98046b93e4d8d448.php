<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title><?php echo e($title ?? 'Desiree Swing Club - Curitiba'); ?></title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<?php echo app('flux')->fluxAppearance(); ?>


<!-- jQuery and Bootstrap dependencies -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<!-- Initialize Summernote -->
<script>
document.addEventListener('livewire:navigated', function() {
    $('.summernote').summernote({
        placeholder: 'Escreva seu texto aqui...',
        tabsize: 2,
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onChange: function(contents, $editable) {
                // This will update any Livewire component that has a 'content' property
                if (typeof window.Livewire !== 'undefined') {
                    window.Livewire.find($editable.closest('[wire\\:id]').getAttribute('wire:id'))
                        .set('content', contents);
                }
            }
        }
    });
});</script>
<?php /**PATH C:\xampp\htdocs\desiree2\resources\views/partials/head.blade.php ENDPATH**/ ?>