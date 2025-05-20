<div
    x-data="{}"
    x-init="
        window.addEventListener('walletUpdated', () => {
            console.log('Evento walletUpdated recebido');

            // Usar apenas um refresh com um pequeno atraso
            setTimeout(() => {
                console.log('Executando refresh único após delay');
                $wire.$refresh();
            }, 500);
        });

        window.addEventListener('walletBalanceUpdated', () => {
            console.log('Evento walletBalanceUpdated recebido');

            // Forçar uma atualização completa do componente
            $wire.$refresh();
        });
    "
>
    <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Gerenciamento de Carteiras</h2>
        </div>

        <!-- Filtros e Busca -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <x-flux::input
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar usuários..."
                icon="magnifying-glass"
            />

            <x-flux::select wire:model.live="filterByBalance">
                <option value="">Todos os saldos</option>
                <option value="positive">Saldo positivo</option>
                <option value="zero">Saldo zero</option>
            </x-flux::select>

            <x-flux::select wire:model.live="perPage">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </x-flux::select>
        </div>

        <!-- Tabela de Usuários e Carteiras -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                            <div class="flex items-center">
                                Usuário
                                @if ($sortBy === 'name')
                                    <span class="ml-1">
                                        @if ($sortDirection === 'asc')
                                            <x-flux::icon name="chevron-up" class="h-4 w-4" />
                                        @else
                                            <x-flux::icon name="chevron-down" class="h-4 w-4" />
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('role')">
                            <div class="flex items-center">
                                Papel
                                @if ($sortBy === 'role')
                                    <span class="ml-1">
                                        @if ($sortDirection === 'asc')
                                            <x-flux::icon name="chevron-up" class="h-4 w-4" />
                                        @else
                                            <x-flux::icon name="chevron-down" class="h-4 w-4" />
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Saldo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->userPhotos->count() > 0)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($user->userPhotos->first()->photo_path) }}" alt="{{ $user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <x-flux::icon name="user" class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ '@' . $user->username }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                       {{ $user->role === 'admin' ? 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200' :
                                       ($user->role === 'vip' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' :
                                       'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($user->wallet)
                                    <span class="{{ $user->wallet->balance > 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}" wire:key="wallet-balance-{{ $user->id }}-{{ time() }}">
                                        R$ {{ number_format($user->wallet->balance, 2, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">
                                        R$ 0,00
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($user->wallet)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $user->wallet->active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' :
                                        'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                        {{ $user->wallet->active ? 'Ativa' : 'Inativa' }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                        Não criada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                   <x-flux::button
                                    wire:click="$dispatch('openModal', ['addFunds', { userId: {{ $user->id }}, userName: '{{ addslashes($user->name) }}' }])"
                                    color="secondary"
                                    size="xs"
                                >
                                    <x-flux::icon name="banknotes" class="w-4 h-4" />
                                </x-flux::button>


                                    <x-flux::button
                                        wire:click="$dispatch('openModal', ['transactions', { userId: {{ $user->id }}, userName: '{{ addslashes($user->name) }}' }])"
                                        color="secondary"
                                        size="xs"
                                    >
                                        <x-flux::icon name="list-bullet" class="w-4 h-4" />
                                    </x-flux::button>

                                    @if($user->wallet)
                                        <x-flux::button wire:click="toggleWalletStatus({{ $user->wallet->id }})" color="{{ $user->wallet->active ? 'danger' : 'success' }}" size="xs">
                                            @if($user->wallet->active)
                                                <x-flux::icon name="lock-closed" class="w-4 h-4" />
                                            @else
                                                <x-flux::icon name="lock-open" class="w-4 h-4" />
                                            @endif
                                        </x-flux::button>
                                    @else
                                        <x-flux::button wire:click="createWallet({{ $user->id }})" color="success" size="xs">
                                            <x-flux::icon name="plus" class="w-4 h-4" />
                                        </x-flux::button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

  <!-- Modal para Adicionar/Remover Fundos -->
  <div
    x-data="{ open: false }"
    x-init="
        window.addEventListener('openModal', event => {
            if (event.detail[0] === 'addFunds') {
                open = true;
                $wire.set('selectedUserId', event.detail[1].userId);
                $wire.set('selectedUserName', event.detail[1].userName);
            }
        });

        window.addEventListener('walletUpdated', () => {
            open = false;
        });
    "
>
    <div
        x-show="open"
        x-cloak
        @keydown.escape.window="open = false; $wire.closeModal()"
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <!-- Backdrop -->
        <div
            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false; $wire.closeModal()"
        ></div>

        <!-- Modal Content -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div
                class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-md w-full p-6 relative mx-auto"
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                @click.away="open = false; $wire.closeModal()"
            >
                <!-- Header -->
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Gerenciar Fundos - {{ $selectedUserName }}
                    </h3>
                </div>

                <!-- Body -->
                <form wire:submit.prevent="addFunds">
                    <div class="mb-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Valor (R$)
                                </label>
                                <input
                                    id="amount"
                                    wire:model.live="amount"
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    placeholder="0.00"
                                    required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                />
                                @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Descrição
                                </label>
                                <textarea
                                    id="description"
                                    wire:model.live="description"
                                    placeholder="Motivo da operação"
                                    required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    rows="3"
                                ></textarea>
                                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="transactionType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tipo de Transação
                                </label>
                                <select
                                    id="transactionType"
                                    wire:model.live="transactionType"
                                    required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                >
                                    <option value="admin_deposit">Depósito Administrativo</option>
                                    <option value="bonus">Bônus</option>
                                    <option value="adjustment">Ajuste</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            @click="open = false; $wire.closeModal()"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        >
                            Cancelar
                        </button>
                        <button
                            type="button"
                            wire:click="subtractFunds"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                            Remover Fundos
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            Adicionar Fundos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Modal para Visualizar Transações -->
    <div
        x-data="{ open: false }"
        x-init="
            window.addEventListener('openModal', event => {
                if (event.detail[0] === 'transactions') {
                    open = true;
                    $wire.set('currentUserId', event.detail[1].userId);
                    $wire.set('currentUserName', event.detail[1].userName);
                    $wire.set('transactionFilter', 'all');
                }
            });

            window.addEventListener('walletUpdated', () => {
                open = false;
            });
        "
    >
        <div
            x-show="open"
            x-cloak
            @keydown.escape.window="open = false; $wire.closeModal()"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <!-- Backdrop -->
            <div
                class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="open = false; $wire.closeModal()"
            ></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div
                    class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-5xl w-full p-6 relative mx-auto"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    @click.away="open = false; $wire.closeModal()"
                >
                    <!-- Header -->
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Transações - {{ $currentUserName }}
                        </h3>
                    </div>

                    <!-- Body -->
                    <div class="mb-4">
                        <div class="space-y-4">
                            <div class="flex flex-wrap gap-2 mb-4">
                                <button
                                    wire:click="setTransactionFilter('all')"
                                    class="px-3 py-1 text-xs font-medium rounded-md {{ $transactionFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}"
                                >
                                    Todas
                                </button>
                                <button
                                    wire:click="setTransactionFilter('deposit')"
                                    class="px-3 py-1 text-xs font-medium rounded-md {{ $transactionFilter === 'deposit' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}"
                                >
                                    Depósitos
                                </button>
                                <button
                                    wire:click="setTransactionFilter('withdrawal')"
                                    class="px-3 py-1 text-xs font-medium rounded-md {{ $transactionFilter === 'withdrawal' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}"
                                >
                                    Saques
                                </button>
                                <button
                                    wire:click="setTransactionFilter('transfer')"
                                    class="px-3 py-1 text-xs font-medium rounded-md {{ $transactionFilter === 'transfer' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}"
                                >
                                    Transferências
                                </button>
                                <button
                                    wire:click="setTransactionFilter('purchase')"
                                    class="px-3 py-1 text-xs font-medium rounded-md {{ $transactionFilter === 'purchase' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}"
                                >
                                    Compras
                                </button>
                                <button
                                    wire:click="setTransactionFilter('admin_deposit')"
                                    class="px-3 py-1 text-xs font-medium rounded-md {{ $transactionFilter === 'admin_deposit' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}"
                                >
                                    Admin
                                </button>
                            </div>

                            @if($transactions && $transactions->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-zinc-700">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Data
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Tipo
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Valor
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Saldo Após
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Descrição
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Origem/Destino
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($transactions as $transaction)
                                                <tr>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            {{ in_array($transaction->type, ['deposit', 'admin_deposit', 'bonus']) ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' :
                                                            (in_array($transaction->type, ['withdrawal', 'purchase']) ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' :
                                                            'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                        <span class="{{ $transaction->amount > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                            R$ {{ number_format(abs($transaction->amount), 2, ',', '.') }}
                                                            {{ $transaction->amount > 0 ? '+' : '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        R$ {{ number_format($transaction->balance_after, 2, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $transaction->description }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if($transaction->source_user_id)
                                                            {{ $transaction->sourceUser->name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $transactions->links() }}
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    Nenhuma transação encontrada.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            @click="open = false; $wire.closeModal()"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        >
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
