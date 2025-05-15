@props([
    'variant' => 'primary',
    'size' => 'base',
    'weight' => 'medium',
    'color' => null,
    'href' => '#',
    'external' => false,
    'underline' => true,
])

@php
    // Definir classes base para cada variante
    $variantClasses = match ($variant) {
        'primary' => 'text-link',
        'subtle' => 'text-link-subtle',
        'accent' => 'text-accent',
        'success' => 'text-success',
        'warning' => 'text-warning',
        'danger' => 'text-danger',
        'info' => 'text-info',
        'disabled' => 'text-disabled pointer-events-none',
        default => 'text-link',
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
        default => 'font-medium',
    };

    // Definir classes de sublinhado
    $underlineClasses = $underline ? 'hover:underline' : '';

    // Definir classes de cor (se especificada manualmente)
    $colorClasses = $color ? "text-$color" : '';

    // Combinar todas as classes
    $classes = trim("$variantClasses $sizeClasses $weightClasses $underlineClasses $colorClasses");

    // Atributos para links externos
    $externalAttributes = $external ? ['target' => '_blank', 'rel' => 'noopener noreferrer'] : [];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes])->merge($externalAttributes) }}>
    {{ $slot }}
</a>
