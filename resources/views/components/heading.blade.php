@props([
    'level' => 2,
    'size' => null,
    'weight' => 'semibold',
    'color' => null,
])

@php
    // Definir o elemento HTML com base no nível
    $element = 'h' . $level;

    // Definir classes de tamanho padrão com base no nível
    $defaultSize = match ((int) $level) {
        1 => '3xl',
        2 => '2xl',
        3 => 'xl',
        4 => 'lg',
        5 => 'base',
        6 => 'sm',
        default => 'xl',
    };

    // Usar o tamanho especificado ou o padrão
    $sizeToUse = $size ?? $defaultSize;

    // Definir classes de tamanho
    $sizeClasses = match ($sizeToUse) {
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
        default => 'text-xl',
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
        default => 'font-semibold',
    };

    // Definir classes de cor (se especificada manualmente)
    $colorClasses = $color ? "text-$color" : 'text-title';

    // Combinar todas as classes
    $classes = trim("$sizeClasses $weightClasses $colorClasses");
@endphp

<{{ $element }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $element }}>
