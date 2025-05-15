@props([
    'label' => null,
    'accept' => null,
    'multiple' => false,
    'error' => null,
    'id' => 'file-' . uniqid(),
    'showFilename' => true,
    'icon' => 'document-text',
    'iconVariant' => 'outline',
    'required' => false,
    'help' => null,
])

<div {{ $attributes->only(['class'])->merge(['class' => 'w-full']) }}>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <label for="{{ $id }}" class="flex items-center justify-center w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
            <x-flux::icon :name="$icon" :variant="$iconVariant" class="w-5 h-5 mr-2" />
            <span>Escolher arquivo{{ $multiple ? 's' : '' }}</span>
            <input
                {{ $attributes->except(['class']) }}
                type="file"
                id="{{ $id }}"
                class="sr-only"
                @if($accept) accept="{{ $accept }}" @endif
                @if($multiple) multiple @endif
                @if($required) required @endif
            >
        </label>
    </div>

    @if($help)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif

    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $error }}</p>
    @endif

    @if($showFilename)
        <div wire:loading.remove {{ $attributes->wire('model') }}>
            @if($attributes->wire('model')->value())
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400 flex items-center">
                    <x-flux::icon :name="$icon" class="w-4 h-4 mr-1" />
                    <span class="file-name truncate">
                        @if(is_array($attributes->wire('model')->value()))
                            {{ count($attributes->wire('model')->value()) }} arquivo(s) selecionado(s)
                        @elseif(is_object($attributes->wire('model')->value()) && method_exists($attributes->wire('model')->value(), 'getClientOriginalName'))
                            {{ $attributes->wire('model')->value()->getClientOriginalName() }}
                        @elseif(is_string($attributes->wire('model')->value()))
                            Arquivo selecionado
                        @else
                            Arquivo selecionado
                        @endif
                    </span>
                </div>
            @endif
        </div>
    @endif

    <div wire:loading {{ $attributes->wire('model') }} class="mt-2">
        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
            <div class="bg-red-600 h-2 rounded-full animate-pulse" style="width: 100%"></div>
        </div>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Carregando arquivo...</div>
    </div>
</div>
