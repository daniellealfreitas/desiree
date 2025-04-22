<div class="container mx-auto p-4">
    <form wire:submit.prevent="store" class="space-y-4">
        {{-- Mensagem de sucesso --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('message') }}
            </div>
        @endif

        {{-- Mensagem de erro --}}
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div>
            <input wire:model="title" type="text" placeholder="Título do conto" 
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('title') 
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <select wire:model="category_id" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
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
            <textarea wire:model="content"
                      class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
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

{{-- Dica: Certifique-se de ter @livewireStyles no <head> e @livewireScripts no <body> do seu layout --}}
{{-- Nota: Para melhor UX, evite usar redirect() no método store do componente Livewire --}}
