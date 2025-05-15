<div>
    <flux:tooltip :content="__('Carteira')" position="bottom">
        <flux:navbar.item href="{{ route('wallet.index') }}" wire:navigate class="!h-10 [&>div>svg]:size-5">
            <div class="flex items-center gap-1.5">
                <x-flux::icon name="wallet" class="h-5 w-5" />
                <span class="font-medium">R$ {{ number_format($balance, 2, ',', '.') }}</span>
            </div>
        </flux:navbar.item>
    </flux:tooltip>
</div>
