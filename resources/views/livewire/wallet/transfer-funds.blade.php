<div>
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transferir Fundos</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Envie dinheiro para outros usuários da plataforma</p>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-zinc-800">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 dark:bg-green-900/30">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <x-flux::icon name="check-circle" class="h-5 w-5 text-green-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit="transfer">
                <div class="mb-6">
                    <label for="username" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Usuário</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="username" id="username" class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:placeholder:text-zinc-400 dark:focus:ring-indigo-500" placeholder="Digite o nome ou username" />
                        
                        @if($showResults && count($searchResults) > 0)
                            <div class="absolute z-10 mt-1 w-full rounded-md bg-white shadow-lg dark:bg-zinc-700">
                                <ul class="max-h-60 overflow-auto rounded-md py-1 text-base sm:text-sm">
                                    @foreach($searchResults as $user)
                                        <li wire:click="selectUser('{{ $user->username }}')" class="relative cursor-pointer select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-indigo-600 hover:text-white dark:text-white dark:hover:bg-indigo-600">
                                            <div class="flex items-center">
                                                <img src="{{ $user->userPhotos->first() ? asset($user->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}" alt="{{ $user->name }}" class="mr-3 h-6 w-6 flex-shrink-0 rounded-full">
                                                <div class="flex flex-col">
                                                    <span class="font-medium">{{ $user->name }}</span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">@{{ $user->username }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    @error('username') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                @if($recipientUser)
                    <div class="mb-6 rounded-md bg-gray-50 p-4 dark:bg-zinc-700">
                        <div class="flex items-center">
                            <img src="{{ $recipientUser->userPhotos->first() ? asset($recipientUser->userPhotos->first()->photo_path) : asset('images/default-avatar.jpg') }}" alt="{{ $recipientUser->name }}" class="mr-3 h-10 w-10 rounded-full">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $recipientUser->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">@{{ $recipientUser->username }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-6">
                    <label for="amount" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Valor (R$)</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">R$</span>
                        </div>
                        <input type="number" wire:model="amount" id="amount" min="1" step="0.01" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:placeholder:text-zinc-400 dark:focus:ring-indigo-500" placeholder="0.00" />
                    </div>
                    @error('amount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Descrição (opcional)</label>
                    <textarea wire:model="description" id="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:placeholder:text-zinc-400 dark:focus:ring-indigo-500" placeholder="Motivo da transferência"></textarea>
                    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('wallet.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-700 dark:text-white dark:ring-zinc-600 dark:hover:bg-zinc-600">
                        <x-flux::icon name="arrow-left" class="mr-1 h-4 w-4" />
                        Voltar
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                        <span wire:loading.remove>
                            <x-flux::icon name="paper-airplane" class="mr-1 h-4 w-4" />
                            Transferir
                        </span>
                        <span wire:loading>
                            <x-flux::icon name="arrow-path" class="mr-1 h-4 w-4 animate-spin" />
                            Processando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
