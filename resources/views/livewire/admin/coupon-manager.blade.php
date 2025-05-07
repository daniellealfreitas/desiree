<div>
    <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Gerenciamento de Cupons</h2>
            <flux:button wire:click="create">
                <flux:icon name="plus" class="h-4 w-4 mr-2" />
                Novo Cupom
            </flux:button>
        </div>
        
        <!-- Filtros e Busca -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Buscar cupons..." 
                icon="magnifying-glass" 
            />
            
            <flux:select wire:model.live="activeFilter">
                <option value="">Todos os status</option>
                <option value="1">Ativos</option>
                <option value="0">Inativos</option>
            </flux:select>
            
            <flux:select wire:model.live="perPage">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </flux:select>
        </div>
        
        <!-- Tabela de Cupons -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('code')">
                            Código
                            @if($sortBy === 'code')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('type')">
                            Tipo
                            @if($sortBy === 'type')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('value')">
                            Valor
                            @if($sortBy === 'value')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Uso
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('expires_at')">
                            Expiração
                            @if($sortBy === 'expires_at')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('active')">
                            Status
                            @if($sortBy === 'active')
                                <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="h-3 w-3 inline" />
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $coupon->code }}
                                </div>
                                @if($coupon->description)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $coupon->description }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $coupon->type === 'percentage' ? 'Percentual' : 'Valor Fixo' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}%
                                    @if($coupon->max_discount > 0)
                                        <span class="text-xs text-gray-400 dark:text-gray-500">
                                            (máx: R$ {{ number_format($coupon->max_discount, 2, ',', '.') }})
                                        </span>
                                    @endif
                                @else
                                    R$ {{ number_format($coupon->value, 2, ',', '.') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($coupon->usage_limit)
                                    {{ $coupon->used }} / {{ $coupon->usage_limit }}
                                @else
                                    {{ $coupon->used }} / ∞
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($coupon->expires_at)
                                    {{ $coupon->expires_at->format('d/m/Y') }}
                                    @if($coupon->expires_at < now())
                                        <span class="text-xs text-red-500 dark:text-red-400">
                                            (expirado)
                                        </span>
                                    @endif
                                @else
                                    Sem expiração
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($coupon->active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        Ativo
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                                        Inativo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <flux:button wire:click="edit({{ $coupon->id }})" variant="secondary" size="xs">
                                    <flux:icon name="pencil-square" class="h-4 w-4" />
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $coupon->id }})" variant="secondary" size="xs" class="ml-2">
                                    <flux:icon name="trash" class="h-4 w-4 text-red-500" />
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Nenhum cupom encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginação -->
        <div class="mt-4">
            {{ $coupons->links() }}
        </div>
    </div>
    
    <!-- Modal de Formulário -->
    <flux:modal wire:model="showModal" title="{{ $isEditing ? 'Editar Cupom' : 'Novo Cupom' }}">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <flux:input 
                        wire:model="code" 
                        label="Código do Cupom" 
                        placeholder="Ex: VERAO2024" 
                        required
                    />
                </div>
                
                <div>
                    <flux:select 
                        wire:model="type" 
                        label="Tipo de Desconto"
                        required
                    >
                        <option value="percentage">Percentual (%)</option>
                        <option value="fixed">Valor Fixo (R$)</option>
                    </flux:select>
                </div>
                
                <div>
                    <flux:input 
                        wire:model="value" 
                        label="Valor do Desconto" 
                        type="number" 
                        step="{{ $type === 'percentage' ? '1' : '0.01' }}" 
                        min="0" 
                        placeholder="{{ $type === 'percentage' ? '10' : '10.00' }}" 
                        required
                    />
                </div>
                
                <div class="sm:col-span-2">
                    <flux:input 
                        wire:model="description" 
                        label="Descrição" 
                        placeholder="Descrição do cupom (opcional)"
                    />
                </div>
                
                <div>
                    <flux:input 
                        wire:model="expiresAt" 
                        label="Data de Expiração" 
                        type="date" 
                        min="{{ date('Y-m-d') }}"
                    />
                </div>
                
                <div>
                    <flux:input 
                        wire:model="usageLimit" 
                        label="Limite de Uso" 
                        type="number" 
                        min="0" 
                        placeholder="Sem limite"
                    />
                </div>
                
                <div>
                    <flux:input 
                        wire:model="minPurchase" 
                        label="Valor Mínimo de Compra" 
                        type="number" 
                        step="0.01" 
                        min="0" 
                        placeholder="0.00"
                    />
                </div>
                
                @if($type === 'percentage')
                    <div>
                        <flux:input 
                            wire:model="maxDiscount" 
                            label="Desconto Máximo (R$)" 
                            type="number" 
                            step="0.01" 
                            min="0" 
                            placeholder="Sem limite"
                        />
                    </div>
                @endif
                
                <div class="sm:col-span-2">
                    <flux:checkbox 
                        wire:model="active" 
                        label="Cupom Ativo"
                    />
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <flux:button type="button" variant="secondary" wire:click="$set('showModal', false)">
                    Cancelar
                </flux:button>
                <flux:button type="submit">
                    {{ $isEditing ? 'Atualizar' : 'Salvar' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
    
    <!-- Modal de Confirmação de Exclusão -->
    <flux:modal wire:model="confirmingDelete" title="Confirmar Exclusão">
        <div class="p-4">
            <p class="text-gray-700 dark:text-gray-300">
                Tem certeza que deseja excluir este cupom? Esta ação não pode ser desfeita.
            </p>
            
            <div class="mt-6 flex justify-end space-x-3">
                <flux:button variant="secondary" wire:click="$set('confirmingDelete', false)">
                    Cancelar
                </flux:button>
                <flux:button variant="danger" wire:click="delete">
                    Excluir
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
