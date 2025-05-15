<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="flex flex-col items-start">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Aparência')" :subheading=" __('Mude a aparência do seu painel')">
        <div x-data="{
            theme: localStorage.theme || 'dark',
            updateTheme(value) {
                this.theme = value;
                localStorage.theme = value;

                if (value === 'dark' || (value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        }" x-init="$watch('theme', value => updateTheme(value))">
            <flux:radio.group variant="segmented" x-model="theme">
                <flux:radio value="light" icon="sun">{{ __('Claro') }}</flux:radio>
                <flux:radio value="dark" icon="moon">{{ __('Escuro') }}</flux:radio>
                <flux:radio value="system" icon="computer-desktop">{{ __('Sistema') }}</flux:radio>
            </flux:radio.group>
        </div>
    </x-settings.layout>
</div>
