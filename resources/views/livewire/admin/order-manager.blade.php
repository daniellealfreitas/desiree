<div>
    <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Gerenciamento de Pedidos</h2>
        </div>

        <!-- Filtros e Busca -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar pedidos..."
                    icon="magnifying-glass"
                />
            </div>

            <div>
                <flux:select wire:model.live="statusFilter">
                    <option value="">Todos os status</option>
                    <option value="{{ App\Models\Order::STATUS_PENDING }}">Pendente</option>
                    <option value="{{ App\Models\Order::STATUS_PROCESSING }}">Processando</option>
                    <option value="{{ App\Models\Order::STATUS_COMPLETED }}">Concluído</option>
                    <option value="{{ App\Models\Order::STATUS_SHIPPED }}">Enviado</option>
                    <option value="{{ App\Models\Order::STATUS_DELIVERED }}">Entregue</option>
                    <option value="{{ App\Models\Order::STATUS_CANCELLED }}">Cancelado</option>
                    <option value="{{ App\Models\Order::STATUS_REFUNDED }}">Reembolsado</option>
                </flux:select>
            </div>

            <div>
                <flux:input
                    wire:model.live="dateFrom"
                    type="date"
                    placeholder="Data inicial"
                    label="De"
                />
            </div>

            <div>
                <flux:input
                    wire:model.live="dateTo"
                    type="date"
                    placeholder="Data final"
                    label="Até"
                />
            </div>
        </div>

        <!-- Tabela de Pedidos -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('id')">
                            Pedido
                            @if($sortBy === 'id')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                            Data
                            @if($sortBy === 'created_at')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('total')">
                            Total
                            @if($sortBy === 'total')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">
                            Status
                            @if($sortBy === 'status')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                #{{ $order->id }}
                                @if($order->payment_id)
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $order->payment_id }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $order->user->name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $order->user->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                R$ {{ number_format($order->getFinalTotal(), 2, ',', '.') }}
                                @if($order->discount > 0)
                                    <div class="text-xs text-green-600 dark:text-green-400">
                                        Desconto: R$ {{ number_format($order->discount, 2, ',', '.') }}
                                    </div>
                                @endif
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
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <flux:button wire:click="viewOrder({{ $order->id }})" variant="secondary" size="xs">
                                    <flux:icon name="eye" class="h-4 w-4" />
                                </flux:button>
                                <flux:button wire:click="editStatus({{ $order->id }})" variant="secondary" size="xs" class="ml-2">
                                    <flux:icon name="pencil-square" class="h-4 w-4" />
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Nenhum pedido encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Modal de Detalhes do Pedido -->
    <flux:modal wire:model="showOrderDetails" title="Detalhes do Pedido #{{ $viewingOrder->id ?? '' }}">
        @if($viewingOrder)
            <div class="p-4 space-y-6">
                <!-- Informações Gerais -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Informações do Pedido</h3>
                        <div class="mt-2 text-sm text-gray-900 dark:text-white">
                            <p><span class="font-medium">Data:</span> {{ $viewingOrder->created_at->format('d/m/Y H:i') }}</p>
                            <p><span class="font-medium">Status:</span> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $this->getStatusClass($viewingOrder->status) }}">{{ $this->getStatusName($viewingOrder->status) }}</span></p>
                            <p><span class="font-medium">Método de Pagamento:</span> {{ ucfirst($viewingOrder->payment_method) }}</p>
                            @if($viewingOrder->payment_id)
                                <p><span class="font-medium">ID do Pagamento:</span> {{ $viewingOrder->payment_id }}</p>
                            @endif
                            @if($viewingOrder->tracking_number)
                                <p><span class="font-medium">Código de Rastreio:</span> {{ $viewingOrder->tracking_number }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Cliente</h3>
                        <div class="mt-2 text-sm text-gray-900 dark:text-white">
                            <p><span class="font-medium">Nome:</span> {{ $viewingOrder->user->name }}</p>
                            <p><span class="font-medium">Email:</span> {{ $viewingOrder->user->email }}</p>
                            @if($viewingOrder->user->phone)
                                <p><span class="font-medium">Telefone:</span> {{ $viewingOrder->user->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Endereço de Entrega</h3>
                        <div class="mt-2 text-sm text-gray-900 dark:text-white">
                            @if($viewingOrder->shipping_address)
                                <p>{{ $viewingOrder->shipping_address['address'] }}</p>
                                <p>{{ $viewingOrder->shipping_address['city'] }} - {{ $viewingOrder->shipping_address['state'] }}</p>
                                <p>{{ $viewingOrder->shipping_address['zip_code'] }}, {{ $viewingOrder->shipping_address['country'] }}</p>
                                <p><span class="font-medium">Telefone:</span> {{ $viewingOrder->shipping_address['phone'] }}</p>
                            @else
                                <p>Nenhum endereço de entrega informado.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Itens do Pedido -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Itens do Pedido</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-zinc-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Preço</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantidade</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($viewingOrder->items as $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img
                                                        src="{{ $item->product->getImageUrl() ?? 'https://placehold.co/100x100/e2e8f0/1e293b?text=Sem+Imagem' }}"
                                                        alt="{{ $item->product->name }}"
                                                        class="h-10 w-10 rounded-md object-cover"
                                                    >
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $item->product->name }}
                                                    </div>
                                                    @if(!empty($item->options))
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            @foreach($item->options as $option => $value)
                                                                <span>{{ ucfirst($option) }}: {{ $value }}</span>
                                                                @if(!$loop->last) | @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            R$ {{ number_format($item->price, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-zinc-700">
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">Subtotal:</td>
                                    <td class="px-6 py-3 text-sm text-gray-500 dark:text-gray-300">R$ {{ number_format($viewingOrder->total, 2, ',', '.') }}</td>
                                </tr>
                                @if($viewingOrder->discount > 0)
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">Desconto:</td>
                                        <td class="px-6 py-3 text-sm text-green-600 dark:text-green-400">- R$ {{ number_format($viewingOrder->discount, 2, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">Frete:</td>
                                    <td class="px-6 py-3 text-sm text-gray-500 dark:text-gray-300">
                                        @if($viewingOrder->shipping_cost > 0)
                                            R$ {{ number_format($viewingOrder->shipping_cost, 2, ',', '.') }}
                                        @else
                                            Grátis
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-700 dark:text-gray-200">Total:</td>
                                    <td class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-200">R$ {{ number_format($viewingOrder->getFinalTotal(), 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Observações -->
                @if($viewingOrder->notes)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Observações</h3>
                        <div class="bg-gray-50 dark:bg-zinc-900 p-3 rounded-md text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                            {{ $viewingOrder->notes }}
                        </div>
                    </div>
                @endif

                <div class="flex justify-end">
                    <flux:button wire:click="$set('showOrderDetails', false)" variant="secondary">
                        Fechar
                    </flux:button>
                </div>
            </div>
        @endif
    </flux:modal>

    <!-- Modal de Atualização de Status -->
    <flux:modal wire:model="showStatusModal" title="Atualizar Status do Pedido #{{ $editingOrder->id ?? '' }}">
        @if($editingOrder)
            <form wire:submit.prevent="updateStatus" class="p-4 space-y-4">
                <div>
                    <flux:select
                        wire:model="newStatus"
                        label="Status do Pedido"
                        required
                    >
                        <option value="{{ App\Models\Order::STATUS_PENDING }}">Pendente</option>
                        <option value="{{ App\Models\Order::STATUS_PROCESSING }}">Processando</option>
                        <option value="{{ App\Models\Order::STATUS_COMPLETED }}">Concluído</option>
                        <option value="{{ App\Models\Order::STATUS_SHIPPED }}">Enviado</option>
                        <option value="{{ App\Models\Order::STATUS_DELIVERED }}">Entregue</option>
                        <option value="{{ App\Models\Order::STATUS_CANCELLED }}">Cancelado</option>
                        <option value="{{ App\Models\Order::STATUS_REFUNDED }}">Reembolsado</option>
                    </flux:select>
                </div>

                <div>
                    <flux:input
                        wire:model="trackingNumber"
                        label="Código de Rastreio"
                        placeholder="Código de rastreio (opcional)"
                    />
                </div>

                <div>
                    <flux:textarea
                        wire:model="statusNote"
                        label="Observação"
                        placeholder="Observação sobre a mudança de status (opcional)"
                        rows="3"
                    />
                </div>

                <div class="flex justify-end space-x-3">
                    <flux:button type="button" variant="secondary" wire:click="$set('showStatusModal', false)">
                        Cancelar
                    </flux:button>
                    <flux:button type="submit">
                        Atualizar Status
                    </flux:button>
                </div>
            </form>
        @endif
    </flux:modal>
</div>
