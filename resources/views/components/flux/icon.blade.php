@props([
    'name' => null,
    'icon' => null,
    'variant' => 'outline',
    'color' => null, // Adicionado para compatibilidade
])

@php
    // Usar name se estiver definido, caso contrário usar icon (para compatibilidade)
    $iconName = $name ?? $icon;
    
    // Usar color se estiver definido, caso contrário usar variant
    $variantToUse = $color ?? $variant;
    
    // Verificar se o componente de ícone existe
    $iconPath = "flux.icon.{$iconName}";
    $iconExists = view()->exists($iconPath);
    
    // Se o ícone não existir, tentar com o nome em kebab-case
    if (!$iconExists) {
        $kebabName = \Illuminate\Support\Str::kebab($iconName);
        $iconPath = "flux.icon.{$kebabName}";
        $iconExists = view()->exists($iconPath);
    }
@endphp

@if($iconExists)
    <x-dynamic-component :component="$iconPath" :variant="$variantToUse" {{ $attributes }} />
@else
    <svg {{ $attributes->merge(['class' => 'w-6 h-6']) }} fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
@endif
