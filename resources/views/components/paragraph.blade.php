@props([
    'variant' => 'body',
    'size' => 'base',
    'weight' => 'normal',
    'color' => null,
    'leading' => 'normal',
])

@php
    // Definir classes base para cada variante
    $variantClasses = match ($variant) {
        'body' => 'text-body',
        'body-light' => 'text-body-light',
        'body-lighter' => 'text-body-lighter',
        'accent' => 'text-accent',
        'success' => 'text-success',
        'warning' => 'text-warning',
        'danger' => 'text-danger',
        'info' => 'text-info',
        'disabled' => 'text-disabled',
        default => 'text-body',
    };

    // Definir classes de tamanho
    $sizeClasses = match ($size) {
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'base' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        default => 'text-base',
    };

    // Definir classes de peso
    $weightClasses = match ($weight) {
        'thin' => 'font-thin',
        'extralight' => 'font-extralight',
        'light' => 'font-light',
        'normal' => 'font-normal',
        'medium' => 'font-medium',
        'semibold' => 'font-semibold',
        'bold' => 'font-bold',
        'extrabold' => 'font-extrabold',
        'black' => 'font-black',
        default => 'font-normal',
    };

    // Definir classes de altura de linha
    $leadingClasses = match ($leading) {
        'none' => 'leading-none',
        'tight' => 'leading-tight',
        'snug' => 'leading-snug',
        'normal' => 'leading-normal',
        'relaxed' => 'leading-relaxed',
        'loose' => 'leading-loose',
        default => 'leading-normal',
    };

    // Definir classes de cor (se especificada manualmente)
    $colorClasses = $color ? "text-$color" : '';

    // Combinar todas as classes
    $classes = trim("$variantClasses $sizeClasses $weightClasses $leadingClasses $colorClasses");
@endphp

<p {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</p>
