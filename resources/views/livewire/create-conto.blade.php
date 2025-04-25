<div class="container mx-auto p-4 relative">
    <!-- Botão fechar -->
    <button x-on:click="$dispatch('close-modal')" 
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <form wire:submit.prevent="store" class="space-y-4">

        {{-- Mensagem de erro --}}
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div>
            <flux:label>Título do Conto:</flux:label>
            <input wire:model="title" type="text" placeholder="Minha experiência na Desiree Club" 
                   class="w-full mt-0.5 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('title') 
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <flux:label>Categoria:</flux:label>
            <select wire:model="category_id" class="w-full mt-0.5 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 bg-zinc-800">
                <option value="">Selecione uma categoria</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <flux:label>Conteúdo do Conto:</flux:label>
            <textarea wire:model="content"
                      class="w-full mt-0.5 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                      rows="10" placeholder="Digite o texto do conto aqui..."></textarea>
            @error('content')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        

        <div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Publicar Conto
            </button>
        </div>
    </form>
</div>