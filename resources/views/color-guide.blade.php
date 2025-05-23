<x-layouts.app :title="__('Guia de Cores')">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Guia de Cores de Texto</h1>
            <p class="text-gray-600 dark:text-gray-400">Este guia apresenta as classes de cores de texto padronizadas para o projeto, garantindo   visual e boa legibilidade em todos os temas.</p>
        </div>

        <x-color-guide />

        <div class="mt-8 p-6 bg-white dark:bg-zinc-800 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Recomendações de Uso</h2>

            <div class="space-y-4">
                <div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Fundos Claros (Tema Claro)</h3>
                    <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Use <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.text-title</code> para títulos principais</li>
                        <li>Use <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.text-subtitle</code> para subtítulos</li>
                        <li>Use <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.text-body</code> para o texto principal</li>
                        <li>Use <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.text-body-light</code> para texto secundário</li>
                        <li>Use <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.text-muted</code> para texto menos importante</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Fundos Escuros (Tema Escuro)</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">As classes são as mesmas, mas as cores são automaticamente ajustadas para garantir boa legibilidade no tema escuro.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Fundos Zinc</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">Para elementos com fundos <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">bg-zinc-*</code>, use a classe <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.text-auto</code> para aplicar automaticamente a cor de texto mais adequada.</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="p-3 bg-zinc-100 rounded"><span class="text-auto">bg-zinc-100 + text-auto</span></div>
                        <div class="p-3 bg-zinc-500 rounded"><span class="text-auto">bg-zinc-500 + text-auto</span></div>
                        <div class="p-3 bg-zinc-800 rounded"><span class="text-auto">bg-zinc-800 + text-auto</span></div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Cores de Status</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">Use as classes de status para mensagens e indicadores:</p>
                    <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Use <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.text-success</code> para mensagens de sucesso</li>
                        <li>Use <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.text-warning</code> para avisos</li>
                        <li>Use <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.text-danger</code> para erros</li>
                        <li>Use <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.text-info</code> para informações</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Efeitos Neon para Texto</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">Use as classes de efeito neon para destacar textos interativos:</p>
                    <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Adicione <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.neon-text</code> junto com uma das classes de cor (ex: <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.neon-text-red</code>)</li>
                        <li>O efeito neon é aplicado automaticamente nos estados hover e focus</li>
                        <li>As cores são ajustadas automaticamente para o tema escuro</li>
                        <li>Cores disponíveis: <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">red</code>, <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">green</code>, <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">blue</code>, <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">purple</code>, <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">pink</code></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Efeitos Neon para Divs</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">Use as classes de efeito neon para destacar cards e containers:</p>
                    <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Adicione <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.neon-box</code> junto com uma das classes de cor (ex: <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">.neon-box-red</code>)</li>
                        <li>O efeito neon é aplicado automaticamente nos estados hover e focus</li>
                        <li>As cores são ajustadas automaticamente para o tema escuro</li>
                        <li>Cores disponíveis: <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">red</code>, <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">green</code>, <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">blue</code>, <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">purple</code>, <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">pink</code></li>
                    </ul>
                    <div class="mt-4 neon-box neon-box-blue p-3 bg-white dark:bg-zinc-900 rounded">
                        <p class="text-body">Exemplo de div com efeito neon azul (passe o mouse para ver)</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Links e Elementos Interativos</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">Todas as classes de link e elementos interativos incluem:</p>
                    <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Transições suaves para mudanças de cor</li>
                        <li>Efeitos neon no hover e focus</li>
                        <li>Cursor not-allowed para elementos desabilitados</li>
                        <li>Compatibilidade com temas claro e escuro</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
