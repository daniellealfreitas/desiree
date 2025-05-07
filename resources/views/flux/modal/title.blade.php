@props(['class' => ''])

<h3 {{ $attributes->merge(['class' => 'text-lg font-medium text-gray-900 dark:text-white ' . $class]) }}>
    {{ $slot }}
</h3>
