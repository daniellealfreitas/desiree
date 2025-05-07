<div>
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Meus Pedidos</h1>

            <!-- Filtros e Busca -->
            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar pedidos..."
                    icon="magnifying-glass"
                />

                <flux:select wire:model.live="statusFilter">
                    <option value="">Todos os status</option>
                    <option value="pending">Pendente</option>
                    <option value="processing">Processando</option>
                    <option value="completed">Concluído</option>
                    <option value="shipped">Enviado</option>
                    <option value="delivered">Entregue</option>
                    <option value="cancelled">Cancelado</option>
                    <option value="refunded">Reembolsado</option>
                </flux:select>

                <flux:select wire:model.live="perPage">
                    <option value="10">10 por página</option>
                    <option value="25">25 por página</option>
                    <option value="50">50 por página</option>
                </flux:select>
            </div>

            <!-- Lista de Pedidos -->
            <div class="mt-6">
                @if($orders->isEmpty())
                    <div class="text-center py-12 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                        <flux:icon name="clipboard-document-list" class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Nenhum pedido encontrado</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Você ainda não realizou nenhum pedido.</p>
                        <div class="mt-6">
                            <flux:button :href="route('shop.index')" variant="primary">
                                Ir para a Loja
                            </flux:button>
                        </div>
                    </div>
                @else
                    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-zinc-900">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pedido</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                #{{ $order->id }}
                                            </div>
                                            @if($order->payment_id)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $order->payment_id }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $this->getStatusClass($order->status) }}">
                                                {{ $this->getStatusName($order->status) }}
                                            </span>
                                            @if($order->tracking_number)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Rastreio: {{ $order->tracking_number }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            R$ {{ number_format($order->getFinalTotal(), 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <flux:button :href="route('shop.order.detail', $order->id)" variant="outline" size="xs">
                                                Detalhes
                                            </flux:button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>

            <div class="mt-8 flex justify-center">
                <flux:button :href="route('shop.index')" variant="outline">
                    <flux:icon name="arrow-left" class="h-4 w-4 mr-2" />
                    Voltar para a Loja
                </flux:button>
            </div>
        </div>
    </div>
</div>
