<div>
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Criar Novo Grupo</h2>

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="create" class="space-y-6">
            <!-- Nome do grupo -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome do Grupo</label>
                <input id="name" type="text" wire:model.live="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required autofocus />
                <div class="mt-1 text-sm text-red-600">@error('name') {{ $message }} @enderror</div>
            </div>

            <!-- Descrição do grupo -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                <textarea id="description" wire:model.live="description" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="4"></textarea>
                <div class="mt-1 text-sm text-red-600">@error('description') {{ $message }} @enderror</div>
            </div>

            <!-- Privacidade do grupo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Privacidade</label>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center">
                        <input id="privacy-public" type="radio" wire:model.live="privacy" value="public" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <label for="privacy-public" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Público</label>
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Qualquer pessoa pode ver e entrar no grupo)
                        </span>
                    </div>

                    <div class="flex items-center">
                        <input id="privacy-private" type="radio" wire:model.live="privacy" value="private" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <label for="privacy-private" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Privado</label>
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Qualquer pessoa pode ver, mas precisa solicitar para entrar)
                        </span>
                    </div>

                    <div class="flex items-center">
                        <input id="privacy-secret" type="radio" wire:model.live="privacy" value="secret" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <label for="privacy-secret" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Secreto</label>
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Apenas membros podem ver o grupo)
                        </span>
                    </div>
                </div>
                <div class="mt-1 text-sm text-red-600">@error('privacy') {{ $message }} @enderror</div>
            </div>

            <!-- Imagem do grupo -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imagem do Grupo</label>
                <div class="mt-2 flex items-center">
                    @if ($image)
                        <div class="relative mr-4">
                            <img src="{{ $image->temporaryUrl() }}" class="w-24 h-24 rounded-lg object-cover">
                            <button type="button" wire:click="$wire.set('image', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    <label for="image-upload" class="cursor-pointer bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        <span>Escolher imagem</span>
                        <input id="image-upload" type="file" wire:model.live="image" class="hidden" accept="image/*">
                    </label>
                </div>
                <div class="mt-1 text-sm text-red-600">@error('image') {{ $message }} @enderror</div>
            </div>

            <!-- Imagem de capa do grupo -->
            <div>
                <label for="coverImage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imagem de Capa</label>
                <div class="mt-2 flex items-center">
                    @if ($coverImage)
                        <div class="relative mr-4">
                            <img src="{{ $coverImage->temporaryUrl() }}" class="w-48 h-24 rounded-lg object-cover">
                            <button type="button" wire:click="$wire.set('coverImage', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    <label for="cover-image-upload" class="cursor-pointer bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        <span>Escolher imagem de capa</span>
                        <input id="cover-image-upload" type="file" wire:model.live="coverImage" class="hidden" accept="image/*">
                    </label>
                </div>
                <div class="mt-1 text-sm text-red-600">@error('coverImage') {{ $message }} @enderror</div>
            </div>

            <!-- Opções adicionais -->
            <div>
                <div class="flex items-center">
                    <input id="posts-require-approval" type="checkbox" wire:model.live="postsRequireApproval" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <label for="posts-require-approval" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Postagens precisam de aprovação</label>
                </div>
                <div class="mt-1 text-sm text-red-600">@error('postsRequireApproval') {{ $message }} @enderror</div>
            </div>

            <!-- Botões de ação -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('grupos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </a>

                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Criar Grupo
                </button>
            </div>
        </form>
    </div>
</div>
