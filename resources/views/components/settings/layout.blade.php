<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item :href="route('settings.profile')" wire:navigate>{{ __('Perfil') }}</flux:navlist.item>
            <flux:navlist.item :href="route('settings.password')" wire:navigate>{{ __('Senha') }}</flux:navlist.item>
            <flux:navlist.item :href="route('settings.preferences')" wire:navigate>{{ __('Preferências') }}</flux:navlist.item>
            <flux:navlist.item :href="route('settings.appearance')" wire:navigate>{{ __('Aparência') }}</flux:navlist.item>
            <flux:navlist.item :href="route('settings.profile-with-avatar')" wire:navigate>{{ __('Foto de Perfil') }}</flux:navlist.item>
            <flux:navlist.item :href="route('settings.profile-with-cover')" wire:navigate>{{ __('Foto de Capa') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
