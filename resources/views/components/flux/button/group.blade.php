@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'inline-flex flex-wrap gap-2 ' . $class]) }}>
    {{ $slot }}
</div>
