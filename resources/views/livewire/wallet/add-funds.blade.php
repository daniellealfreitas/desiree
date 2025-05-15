<div>
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Adicionar Fundos</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Adicione dinheiro à sua carteira para usar na plataforma</p>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-zinc-800">
            <form wire:submit="createCheckoutSession">
                <div class="mb-6">
                    <label for="amount" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Valor (R$)</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">R$</span>
                        </div>
                        <input type="number" wire:model="amount" id="amount" min="10" step="0.01" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:placeholder:text-zinc-400 dark:focus:ring-indigo-500" placeholder="0.00" />
                    </div>
                    @error('amount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Você será redirecionado para o Stripe para concluir o pagamento de forma segura.</p>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('wallet.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600">
                        <x-flux::icon name="arrow-left" class="mr-1 h-4 w-4" />
                        Voltar
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                        <span wire:loading.remove>
                            <x-flux::icon name="credit-card" class="mr-1 h-4 w-4" />
                            Prosseguir para Pagamento
                        </span>
                        <span wire:loading>
                            <x-flux::icon name="arrow-path" class="mr-1 h-4 w-4 animate-spin" />
                            Processando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @script
        $wire.on('openCheckout', ({ url }) => {
            window.open(url, '_self');
        });
    @endscript
</div>
