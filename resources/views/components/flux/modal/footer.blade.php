@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex justify-end space-x-3 mt-6 ' . $class]) }}>
    {{ $slot }}
</div>
