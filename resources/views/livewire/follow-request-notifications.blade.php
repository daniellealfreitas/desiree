@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="relative">
    <flux:dropdown class="max-lg:hidden">
        <flux:navbar.item icon="user-plus" :badge="count($followRequests)">
            Solicitações
        </flux:navbar.item>
        <flux:navmenu class="w-80">
            @if(count($followRequests) > 0)
                @foreach($followRequests as $request)
                    <div class="p-3 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <div class="flex items-center gap-2 mb-2">
                            <img src="{{ !empty($request->sender->userPhotos->first()) ? Storage::url($request->sender->userPhotos->first()->photo_path) : asset('images/users/default.jpg') }}"
                                 class="w-8 h-8 rounded-full object-cover">
                            <div class="flex-1">
                                <p class="text-sm">
                                    <a href="/{{ $request->sender->username }}" class="font-semibold hover:underline">
                                        {{ $request->sender->username }}
                                    </a>
                                    quer seguir você
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2 justify-end">
                            <button wire:click="accept('{{ $request->id }}')"
                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded-full hover:bg-blue-600">
                                Aceitar
                            </button>
                            <button wire:click="reject('{{ $request->id }}')"
                                    class="px-3 py-1 bg-gray-500 text-white text-sm rounded-full hover:bg-gray-600">
                                Rejeitar
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-3">
                    <p class="text-sm text-gray-500">Nenhuma solicitação</p>
                </div>
            @endif
        </flux:navmenu>
    </flux:dropdown>
</div>
