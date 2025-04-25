<x-layouts.app :title="__('Contos')">

    <div class="container mx-auto max-w-6xl p-4">
        <!-- Mensagem de sucesso -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('message') }}
            </div>
        @endif

        <!-- Botão para abrir o modal -->
        <div class="flex justify-end mb-4">
            <button 
                x-data 
                x-on:click="$dispatch('open-modal')" 
                class="bg-purple-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-purple-700 hover:shadow-lg transition duration-300 ease-in-out">
                {{ __('Criar Conto') }}
            </button>
        </div>

        <!-- Lista de contos -->
        <livewire:list-contos />
    </div>

    <!-- Modal -->
    <div 
        x-data="{show : false}"
        x-show="show"
        x-on:open-modal.window="show = true"
        x-on:close-modal.window="show = false"
        x-on:keydown.escape.window="show = false"
        x-on:click.away="show = false"
        class="fixed inset-0 flex items-center justify-center bg-zinc-800 z-50">
        <div class="rounded-lg shadow-lg p-6 w-full max-w-lg">
            <livewire:create-conto />
        </div>
    </div>

</x-layouts.app>
