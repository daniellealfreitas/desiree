<x-layouts.app>
    <div class="container mx-auto w-full p-6">
        @if(isset($conto))
            <div class="shadow rounded-lg p-6">
                <h1 class="text-3xl font-bold mb-2">{{ $conto->title }}</h1>
                <span class="text-sm text-gray-500">
                    Por {{ $conto->user->name ?? 'Autor desconhecido' }} 
                    em {{ $conto->created_at->format('d/m/Y') }}
                </span>
                <div class="my-4">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                        {{ $conto->category->name ?? 'Sem categoria' }}
                    </span>
                </div>
                <div class="prose max-w-none">
                    {{ $conto->content }}
                </div>
                @if(auth()->check() && auth()->id() === $conto->user_id)
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('contos.edit', $conto->id) }}" class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600 transition">
                            Editar
                        </a>
                        <form action="{{ route('contos.destroy', $conto->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded hover:bg-red-600 transition">
                                Excluir
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-red-100 text-red-800 p-4 rounded">
                Conto não encontrado.
            </div>
        @endif
        <div class="mt-6">
            <a href="{{ route('contos') }}" class="text-white hover:underline">← Voltar à lista</a>
        </div>
    </div>
</x-layouts.app>