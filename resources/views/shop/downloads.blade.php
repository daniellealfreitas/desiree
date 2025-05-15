<x-layouts.app :title="__('Meus Downloads')">
    <div class="bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Meus Downloads</h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Aqui você encontra todos os produtos digitais que você adquiriu.
                </p>
            </div>
            
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-md">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg overflow-hidden">
                @if(count($downloads) > 0)
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Produto
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Pedido
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Downloads
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($downloads as $downloadItem)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img
                                                    src="{{ $downloadItem['item']->product->getImageUrl() ?? 'https://placehold.co/100x100/e2e8f0/1e293b?text=Digital' }}"
                                                    alt="{{ $downloadItem['item']->product->name }}"
                                                    class="h-10 w-10 rounded-full object-cover"
                                                >
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $downloadItem['item']->product->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $downloadItem['item']->product->digital_file_name ?: 'Arquivo digital' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        #{{ $downloadItem['item']->order->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($downloadItem['is_valid'])
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                Disponível
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                                Expirado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $downloadItem['download']->download_count }} 
                                        @if($downloadItem['item']->product->download_limit)
                                            / {{ $downloadItem['item']->product->download_limit }}
                                        @endif
                                        
                                        @if($downloadItem['download']->expires_at)
                                            <div class="text-xs mt-1">
                                                Expira em: {{ $downloadItem['download']->expires_at->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($downloadItem['is_valid'])
                                            <a href="{{ route('shop.downloads.download', $downloadItem['download']->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                <flux:button size="xs">
                                                    <flux:icon name="arrow-down-tray" class="h-4 w-4 mr-1" />
                                                    Download
                                                </flux:button>
                                            </a>
                                        @else
                                            <flux:button size="xs" disabled>
                                                <flux:icon name="lock-closed" class="h-4 w-4 mr-1" />
                                                Indisponível
                                            </flux:button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-center">
                        <flux:icon name="document" class="h-12 w-12 mx-auto text-gray-400 dark:text-gray-500 mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum download disponível</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            Você ainda não adquiriu nenhum produto digital.
                        </p>
                        <flux:button :href="route('shop.index')">
                            Explorar Produtos
                        </flux:button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
