<div>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <div class="flex space-x-2">
                <flux:button :href="route('shop.index')" variant="outline" size="sm">
                    <flux:icon name="shopping-bag" class="h-4 w-4 mr-2" />
                    Ver Loja
                </flux:button>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total de Vendas -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <flux:icon name="banknotes" class="h-6 w-6 text-white" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Vendas</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white">R$ {{ number_format($totalSales, 2, ',', '.') }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total de Pedidos -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <flux:icon name="clipboard-document-list" class="h-6 w-6 text-white" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Pedidos</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalOrders }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total de Produtos -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <flux:icon name="cube" class="h-6 w-6 text-white" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Produtos</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalProducts }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total de Usuários -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <flux:icon name="users" class="h-6 w-6 text-white" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Usuários</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalUsers }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Vendas e Produtos com Estoque Baixo -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <!-- Gráfico de Vendas -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Vendas dos Últimos 7 Dias</h3>
                    <div class="mt-2 h-64 relative">
                        <!-- Gráfico simplificado -->
                        <div class="absolute inset-0 flex items-end">
                            @foreach($salesData as $data)
                                <div class="flex-1 flex flex-col items-center">
                                    <div class="w-full px-2">
                                        <div
                                            class="bg-indigo-500 rounded-t"
                                            style="height: {{ $totalSales > 0 ? ($data['sales'] / max(array_column($salesData, 'sales')) * 100) : 0 }}%"
                                        ></div>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $data['date'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produtos com Estoque Baixo -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Produtos com Estoque Baixo</h3>
                    <div class="mt-2 max-h-64 overflow-y-auto">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($lowStockProducts as $product)
                                <li class="py-3 flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ $product->getImageUrl() ?? 'https://placehold.co/100x100/e2e8f0/1e293b?text=Sem+Imagem' }}" alt="{{ $product->name }}">
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Estoque: {{ $product->stock }}</p>
                                    </div>
                                    <div>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->stock === 0 ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' }}">
                                            {{ $product->stock === 0 ? 'Esgotado' : 'Baixo' }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Nenhum produto com estoque baixo.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pedidos Recentes e Produtos Mais Vendidos -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <!-- Pedidos Recentes -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Pedidos Recentes</h3>
                    <div class="mt-2 max-h-64 overflow-y-auto">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentOrders as $order)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Pedido #{{ $order->id }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $this->getStatusClass($order->status) }}">
                                            {{ $this->getStatusName($order->status) }}
                                        </span>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white text-right mt-1">
                                            R$ {{ number_format($order->total, 2, ',', '.') }}
                                        </p>
                                    </div>
                                </li>
                            @empty
                                <li class="py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Nenhum pedido recente.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Produtos Mais Vendidos -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Produtos Mais Vendidos</h3>
                    <div class="mt-2 max-h-64 overflow-y-auto">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($topSellingProducts as $product)
                                <li class="py-3 flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ App\Models\Product::find($product->id)->getImageUrl() ?? 'https://placehold.co/100x100/e2e8f0/1e293b?text=Sem+Imagem' }}" alt="{{ $product->name }}">
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->total_quantity }} unidades vendidas</p>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        R$ {{ number_format($product->total_sales, 2, ',', '.') }}
                                    </div>
                                </li>
                            @empty
                                <li class="py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Nenhum produto vendido ainda.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

