<div>
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sacar Fundos</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Retire dinheiro da sua carteira para sua conta bancária via PIX</p>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-zinc-800">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 dark:bg-green-900/30">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <x-flux::icon name="check-circle" class="h-5 w-5 text-green-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit="withdraw">
                <div class="mb-6">
                    <label for="amount" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Valor (R$)</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">R$</span>
                        </div>
                        <input type="number" wire:model="amount" id="amount" min="50" step="0.01" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:placeholder:text-zinc-400 dark:focus:ring-indigo-500" placeholder="0.00" />
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Valor mínimo para saque: R$ 50,00</p>
                    @error('amount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="pixKeyType" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tipo de Chave PIX</label>
                    <select wire:model="pixKeyType" id="pixKeyType" class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:focus:ring-indigo-500">
                        <option value="cpf">CPF</option>
                        <option value="cnpj">CNPJ</option>
                        <option value="email">E-mail</option>
                        <option value="phone">Telefone</option>
                        <option value="random">Chave Aleatória</option>
                    </select>
                    @error('pixKeyType') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="pixKey" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Chave PIX</label>
                    <input type="text" wire:model="pixKey" id="pixKey" class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:placeholder:text-zinc-400 dark:focus:ring-indigo-500" placeholder="Digite sua chave PIX" />
                    @error('pixKey') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <div class="rounded-md bg-yellow-50 p-4 dark:bg-yellow-900/30">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <x-flux::icon name="exclamation-triangle" class="h-5 w-5 text-yellow-400" />
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Informações importantes</h3>
                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                    <ul class="list-disc space-y-1 pl-5">
                                        <li>Saques são processados em até 24 horas úteis.</li>
                                        <li>Certifique-se de que a chave PIX informada está correta.</li>
                                        <li>Taxa de saque: Gratuita.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('wallet.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600">
                        <x-flux::icon name="arrow-left" class="mr-1 h-4 w-4" />
                        Voltar
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                        <span wire:loading.remove>
                            <x-flux::icon name="arrow-down-on-square" class="mr-1 h-4 w-4" />
                            Solicitar Saque
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
</div>
