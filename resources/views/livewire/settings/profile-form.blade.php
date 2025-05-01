<form wire:submit="updateProfile" class="space-y-6">
    <div class="space-y-4">
        <flux:input wire:model="name" :label="__('Nome')" type="text" required autofocus autocomplete="name" />
        
        <flux:input wire:model="username" :label="__('Nome de usuário')" type="text" required autocomplete="username" />

        <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

        <flux:select wire:model="sexo" :label="__('Sexo')" :options="[
            '' => 'Selecione...',
            'casal' => 'Casal',
            'homem' => 'Homem',
            'mulher' => 'Mulher'
        ]" />

        <flux:input wire:model="aniversario" :label="__('Data de Nascimento')" type="date" />

        <flux:toggle wire:model="privado" :label="__('Perfil Privado')" />

        <flux:textarea wire:model="bio" :label="__('Sobre você')" rows="4" />

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">
                {{ __('Salvar') }}
            </flux:button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Salvo.') }}
            </x-action-message>
        </div>
    </div>
</form>