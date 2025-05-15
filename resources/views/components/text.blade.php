@props([
    'variant' => 'body',
    'as' => 'p',
    'size' => null,
    'weight' => null,
    'color' => null,
])

@php
    // Definir classes base para cada variante
    $variantClasses = match ($variant) {
        'title' => 'text-title',
        'subtitle' => 'text-subtitle',
        'body' => 'text-body',
        'body-light' => 'text-body-light',
        'body-lighter' => 'text-body-lighter',
        'link' => 'text-link hover:underline',
        'link-subtle' => 'text-link-subtle hover:underline',
        'label' => 'text-label',
        'accent' => 'text-accent',
        'success' => 'text-success',
        'warning' => 'text-warning',
        'danger' => 'text-danger',
        'info' => 'text-info',
        'disabled' => 'text-disabled',
        'price' => 'text-price',
        'price-discount' => 'text-price-discount',
        'price-old' => 'text-price-old',
        default => 'text-body',
    };

    // Definir classes de tamanho
    $sizeClasses = match ($size) {
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'base' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        '2xl' => 'text-2xl',
        '3xl' => 'text-3xl',
        '4xl' => 'text-4xl',
        '5xl' => 'text-5xl',
        '6xl' => 'text-6xl',
        default => '',
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
        default => '',
    };

    // Definir classes de cor (se especificada manualmente)
    $colorClasses = $color ? "text-$color" : '';

    // Combinar todas as classes
    $classes = trim("$variantClasses $sizeClasses $weightClasses $colorClasses");
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $as }}>
