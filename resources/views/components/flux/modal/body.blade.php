@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'mb-4 ' . $class]) }}>
    {{ $slot }}
</div>
