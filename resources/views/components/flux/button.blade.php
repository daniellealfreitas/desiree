@props([
    'variant' => 'primary',
    'color' => null, // Adicionado para compatibilidade
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'disabled' => false,
    'type' => 'button',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none';

    // Usar color se estiver definido, caso contrário usar variant
    $variantToUse = $color ?? $variant;

    $variantClasses = match ($variantToUse) {
        'primary' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'secondary' => 'bg-gray-200 text-gray-800 hover:bg-gray-300 focus:ring-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600',
        'outline' => 'border border-gray-300 bg-transparent text-gray-700 hover:bg-gray-50 focus:ring-gray-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:text-gray-300 dark:hover:bg-gray-800',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        default => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    };

    $sizeClasses = match ($size) {
        'xs' => 'text-xs px-2 py-1 rounded',
        'sm' => 'text-sm px-3 py-1.5 rounded-md',
        'md' => 'text-sm px-4 py-2 rounded-md',
        'lg' => 'text-base px-5 py-2.5 rounded-lg',
        'xl' => 'text-lg px-6 py-3 rounded-lg',
        default => 'text-sm px-4 py-2 rounded-md',
    };

    $iconClasses = $icon ? ($slot->isEmpty() ? '' : ($iconPosition === 'left' ? 'mr-2' : 'ml-2')) : '';

    $classes = $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses;

    $attributes = $attributes->class([$classes])->merge([
        'type' => $href ? null : $type,
        'disabled' => $disabled,
    ]);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes }}>
        @if ($icon && $iconPosition === 'left')
            <x-flux::icon :name="$icon" class="w-5 h-5 {{ $iconClasses }}" />
        @endif

        {{ $slot }}

        @if ($icon && $iconPosition === 'right')
            <x-flux::icon :name="$icon" class="w-5 h-5 {{ $iconClasses }}" />
        @endif
    </a>
@else
    <button {{ $attributes }}>
        @if ($icon && $iconPosition === 'left')
            <x-flux::icon :name="$icon" class="w-5 h-5 {{ $iconClasses }}" />
        @endif

        {{ $slot }}

        @if ($icon && $iconPosition === 'right')
            <x-flux::icon :name="$icon" class="w-5 h-5 {{ $iconClasses }}" />
        @endif
    </button>
@endif
