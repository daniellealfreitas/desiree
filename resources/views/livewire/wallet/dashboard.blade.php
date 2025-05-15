<div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Minha Carteira</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Gerencie seu saldo e transações</p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Saldo da Carteira -->
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-zinc-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Saldo Disponível</h2>
                    <x-flux::icon name="wallet" class="h-6 w-6 text-indigo-500" />
                </div>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">R$ {{ number_format($wallet->balance, 2, ',', '.') }}</p>
                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('wallet.add-funds') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        <x-flux::icon name="plus" class="mr-1 h-4 w-4" />
                        Adicionar
                    </a>
                    <a href="{{ route('wallet.transfer') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600">
                        <x-flux::icon name="arrow-right" class="mr-1 h-4 w-4" />
                        Transferir
                    </a>
                    <a href="{{ route('wallet.withdraw') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600">
                        <x-flux::icon name="arrow-down" class="mr-1 h-4 w-4" />
                        Sacar
                    </a>
                </div>
            </div>

            <!-- Estatísticas Rápidas -->
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-zinc-800">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Transações Recentes</h2>
                <div class="mt-2 space-y-3">
                    @php
                        $recentTransactions = $transactions->take(3);
                    @endphp
                    
                    @forelse($recentTransactions as $transaction)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-2 dark:border-zinc-700">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->type_text }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="text-sm font-medium {{ $transaction->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->formatted_amount }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma transação recente</p>
                    @endforelse
                </div>
            </div>

            <!-- Links Rápidos -->
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-zinc-800">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Links Rápidos</h2>
                <div class="mt-4 space-y-3">
                    <a href="{{ route('shop.index') }}" class="flex items-center rounded-md bg-gray-50 px-4 py-3 text-sm font-medium text-gray-900 hover:bg-gray-100 dark:bg-zinc-700 dark:text-white dark:hover:bg-zinc-600">
                        <x-flux::icon name="shopping-bag" class="mr-3 h-5 w-5 text-indigo-500" />
                        Visitar Loja
                    </a>
                    <a href="{{ route('shop.cart') }}" class="flex items-center rounded-md bg-gray-50 px-4 py-3 text-sm font-medium text-gray-900 hover:bg-gray-100 dark:bg-zinc-700 dark:text-white dark:hover:bg-zinc-600">
                        <x-flux::icon name="shopping-cart" class="mr-3 h-5 w-5 text-indigo-500" />
                        Meu Carrinho
                    </a>
                    <a href="{{ route('shop.user.orders') }}" class="flex items-center rounded-md bg-gray-50 px-4 py-3 text-sm font-medium text-gray-900 hover:bg-gray-100 dark:bg-zinc-700 dark:text-white dark:hover:bg-zinc-600">
                        <x-flux::icon name="clipboard-document-list" class="mr-3 h-5 w-5 text-indigo-500" />
                        Meus Pedidos
                    </a>
                </div>
            </div>
        </div>

        <!-- Histórico de Transações -->
        <div class="mt-8 rounded-lg bg-white p-6 shadow-sm dark:bg-zinc-800">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Histórico de Transações</h2>
                
                <div class="flex space-x-2">
                    <button wire:click="setFilter('all')" class="rounded-md px-3 py-1.5 text-sm font-medium {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600' }}">
                        Todas
                    </button>
                    <button wire:click="setFilter('deposit')" class="rounded-md px-3 py-1.5 text-sm font-medium {{ $filter === 'deposit' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600' }}">
                        Depósitos
                    </button>
                    <button wire:click="setFilter('withdrawal')" class="rounded-md px-3 py-1.5 text-sm font-medium {{ $filter === 'withdrawal' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600' }}">
                        Saques
                    </button>
                    <button wire:click="setFilter('transfer_in')" class="rounded-md px-3 py-1.5 text-sm font-medium {{ $filter === 'transfer_in' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600' }}">
                        Recebidas
                    </button>
                    <button wire:click="setFilter('transfer_out')" class="rounded-md px-3 py-1.5 text-sm font-medium {{ $filter === 'transfer_out' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600' }}">
                        Enviadas
                    </button>
                    <button wire:click="setFilter('purchase')" class="rounded-md px-3 py-1.5 text-sm font-medium {{ $filter === 'purchase' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600' }}">
                        Compras
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Data</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Tipo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Descrição</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Valor</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Saldo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-zinc-700 dark:bg-zinc-800">
                        @forelse($transactions as $transaction)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $transaction->type_text }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    @if($transaction->type === 'transfer_in' && $transaction->sourceUser)
                                        Recebido de {{ $transaction->sourceUser->name }}
                                    @elseif($transaction->type === 'transfer_out' && $transaction->sourceUser)
                                        Enviado para {{ $transaction->sourceUser->name }}
                                    @else
                                        {{ $transaction->description ?: '-' }}
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium {{ $transaction->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->formatted_amount }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $transaction->formatted_balance }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $transaction->status_text }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Nenhuma transação encontrada
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
