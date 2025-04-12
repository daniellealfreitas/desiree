<div class="space-y-4">
    @if($requests->count() > 0)
        @foreach($requests as $request)
            <div class="flex items-center justify-between p-4 bg-zinc-900 rounded-lg">
                <div class="flex items-center space-x-3">
                    <img src="{{ !empty($request->sender->userPhotos->first()) ? Storage::url($request->sender->userPhotos->first()->photo_path) : asset('images/users/default.jpg') }}" 
                         class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h3 class="font-semibold text-white">{{ $request->sender->name }}</h3>
                        <p class="text-sm text-gray-400">Quer seguir você</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button wire:click="accept({{ $request->id }})" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Aceitar
                    </button>
                    <button wire:click="reject({{ $request->id }})" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Rejeitar
                    </button>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-8 text-gray-500">
            Nenhuma solicitação pendente
        </div>
    @endif
</div>
