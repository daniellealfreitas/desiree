@props(['class' => ''])

<div {{ $attributes->merge(['class' => $class]) }} x-on:click="show = false">
    {{ $slot }}
</div>
