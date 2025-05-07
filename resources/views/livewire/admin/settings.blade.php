<div>
    <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Configurações do Sistema</h2>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Configurações Gerais -->
            <div class="bg-gray-50 dark:bg-zinc-900 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Configurações Gerais</h3>

                <form wire:submit.prevent="saveGeneralSettings" class="space-y-4">
                    <div>
                        <flux:input
                            wire:model="siteName"
                            label="Nome do Site"
                            placeholder="Nome do site"
                            required
                        />
                    </div>

                    <div>
                        <flux:textarea
                            wire:model="siteDescription"
                            label="Descrição do Site"
                            placeholder="Descrição do site"
                            rows="3"
                        />
                    </div>

                    <div>
                        <flux:input
                            wire:model="contactEmail"
                            label="Email de Contato"
                            type="email"
                            placeholder="email@exemplo.com"
                            required
                        />
                    </div>

                    <div>
                        <flux:checkbox
                            wire:model="enableRegistration"
                            label="Permitir Novos Registros"
                        />
                    </div>

                    <div>
                        <flux:checkbox
                            wire:model="enableShop"
                            label="Habilitar Loja"
                        />
                    </div>

                    <div>
                        <flux:button type="submit">
                            Salvar Configurações Gerais
                        </flux:button>
                    </div>
                </form>
            </div>

            <!-- Configurações da Loja -->
            <div class="bg-gray-50 dark:bg-zinc-900 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Configurações da Loja</h3>

                <form wire:submit.prevent="saveShopSettings" class="space-y-4">
                    <div>
                        <flux:select
                            wire:model="defaultCurrency"
                            label="Moeda Padrão"
                            required
                        >
                            <option value="BRL">Real Brasileiro (BRL)</option>
                            <option value="USD">Dólar Americano (USD)</option>
                            <option value="EUR">Euro (EUR)</option>
                        </flux:select>
                    </div>

                    <div>
                        <flux:input
                            wire:model="taxRate"
                            label="Taxa de Imposto (%)"
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            required
                        />
                    </div>

                    <div>
                        <flux:input
                            wire:model="shippingFee"
                            label="Taxa de Entrega Padrão (R$)"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                        />
                    </div>

                    <div>
                        <flux:input
                            wire:model="freeShippingThreshold"
                            label="Valor Mínimo para Frete Grátis (R$)"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                        />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Use 0 para desativar o frete grátis.
                        </p>
                    </div>

                    <div>
                        <flux:button type="submit">
                            Salvar Configurações da Loja
                        </flux:button>
                    </div>
                </form>
            </div>

            <!-- Configurações de Analytics -->
            <div class="bg-gray-50 dark:bg-zinc-900 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Configurações de Analytics</h3>

                <form wire:submit.prevent="saveAnalyticsSettings" class="space-y-4">
                    <div>
                        <flux:textarea
                            wire:model="analyticsCode"
                            label="Código de Analytics"
                            placeholder="Cole seu código de rastreamento do Google Analytics aqui"
                            rows="5"
                        />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Cole o código de rastreamento completo, incluindo as tags &lt;script&gt;.
                        </p>
                    </div>

                    <div>
                        <flux:button type="submit">
                            Salvar Configurações de Analytics
                        </flux:button>
                    </div>
                </form>
            </div>

            <!-- Manutenção e Cache -->
            <div class="bg-gray-50 dark:bg-zinc-900 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Manutenção e Cache</h3>

                <div class="space-y-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Modo de Manutenção</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                            Quando ativado, o site ficará indisponível para usuários normais. Apenas administradores poderão acessar.
                        </p>
                        <div class="flex items-center">
                            <flux:checkbox
                                wire:model="maintenanceMode"
                                label="Ativar Modo de Manutenção"
                            />
                            <flux:button wire:click="toggleMaintenanceMode" variant="outline" class="ml-4">
                                {{ $maintenanceMode ? 'Desativar' : 'Ativar' }}
                            </flux:button>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limpar Cache</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                            Limpa o cache do sistema, incluindo views, rotas e configurações.
                        </p>
                        <flux:button wire:click="clearCache" variant="outline">
                            Limpar Cache
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
