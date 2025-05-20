<div>
    @if($showModal)
        <flux:modal wire:model="showModal" size="md">
            <flux:modal.header>
                <flux:heading size="sm">Enviar Charm para {{ $user->name }}</flux:heading>
            </flux:modal.header>

            <flux:modal.body>
                @if($success)
                    <div class="text-center">
                        <x-flux::icon name="check-circle" class="w-16 h-16 mx-auto text-green-500 mb-4" />
                        <p class="mb-4">Charm enviado com sucesso!</p>
                        <p class="text-sm text-gray-500">{{ $user->name }} recebeu seu charm e foi notificado.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        <div>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">
                                Escolha um charm para enviar para {{ $user->name }}:
                            </p>

                            <div class="grid grid-cols-2 gap-3 mb-4">
                                @foreach($charms as $type => $charm)
                                    <button
                                        wire:click="selectCharm('{{ $type }}')"
                                        class="p-4 border rounded-lg text-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors
                                            {{ $selectedCharm == $type ? 'bg-red-100 dark:bg-red-900 border-red-500' : '' }}"
                                    >
                                        <div class="flex flex-col items-center">
                                            <x-flux::icon name="{{ $charm['icon'] }}" class="w-8 h-8 mb-2 {{ $selectedCharm == $type ? 'text-red-500' : 'text-gray-500' }}" />
                                            <div class="font-bold text-gray-300">{{ $charm['name'] }}</div>
                                            <div class="text-sm text-gray-400">R$ {{ number_format($charm['price'], 2, ',', '.') }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $charm['description'] }}</div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>

                            @error('selectedCharm')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror

                            @error('charm')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensagem (opcional)</label>
                            <textarea
                                wire:model.defer="message"
                                rows="2"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 dark:bg-zinc-700 dark:border-gray-600 text-gray-300"
                                placeholder="Escreva uma mensagem pessoal..."
                            ></textarea>
                            @error('message')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        @error('payment')
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Ao enviar um charm, você transfere créditos da sua carteira para {{ $user->name }}.
                            </p>
                        </div>
                    </div>
                @endif
            </flux:modal.body>

            <flux:modal.footer>
                <div class="flex justify-between w-full">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Seu saldo: R$ {{ number_format($walletBalance, 2, ',', '.') }}
                            </p>
                            <button wire:click="refreshWalletBalance" class="text-gray-400 hover:text-gray-600" title="Atualizar saldo">
                                <x-flux::icon name="arrow-path" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <flux:button wire:click="closeModal" variant="primary">
                            {{ $success ? 'Fechar' : 'Cancelar' }}
                        </flux:button>

                        @if(!$success)
                            <flux:button
                                wire:click="sendCharm"
                                variant="primary"
                                wire:loading.attr="disabled"
                                wire:target="sendCharm"
                                :disabled="!$selectedCharm || $processing || ($selectedCharm && $charms[$selectedCharm]['price'] > $walletBalance)"
                            >
                                <span wire:loading.remove wire:target="sendCharm">
                                    Enviar {{ $selectedCharm ? $charms[$selectedCharm]['name'] : 'Charm' }}
                                </span>
                                <span wire:loading wire:target="sendCharm">
                                    Processando...
                                </span>
                            </flux:button>
                        @endif
                    </div>
                </div>
            </flux:modal.footer>
        </flux:modal>
    @endif
</div>
