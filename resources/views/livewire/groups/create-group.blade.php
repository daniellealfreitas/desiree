<div>
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Criar Novo Grupo</h2>

        <form wire:submit.prevent="create" class="space-y-6">
            <!-- Nome do grupo -->
            <div>
                <flux:label for="name" value="Nome do Grupo" />
                <flux:input id="name" type="text" wire:model="name" class="w-full" required autofocus />
                <div class="mt-1 text-sm text-red-600">@error('name') {{ $message }} @enderror</div>
            </div>

            <!-- Descrição do grupo -->
            <div>
                <flux:label for="description" value="Descrição" />
                <flux:textarea id="description" wire:model="description" class="w-full" rows="4" />
                <div class="mt-1 text-sm text-red-600">@error('description') {{ $message }} @enderror</div>
            </div>

            <!-- Privacidade do grupo -->
            <div>
                <flux:label value="Privacidade" />
                <div class="mt-2 space-y-2">
                    <div class="flex items-center">
                        <flux:radio id="privacy-public" wire:model="privacy" value="public" />
                        <flux:label for="privacy-public" value="Público" class="ml-2" />
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Qualquer pessoa pode ver e entrar no grupo)
                        </span>
                    </div>

                    <div class="flex items-center">
                        <flux:radio id="privacy-private" wire:model="privacy" value="private" />
                        <flux:label for="privacy-private" value="Privado" class="ml-2" />
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Qualquer pessoa pode ver, mas precisa solicitar para entrar)
                        </span>
                    </div>

                    <div class="flex items-center">
                        <flux:radio id="privacy-secret" wire:model="privacy" value="secret" />
                        <flux:label for="privacy-secret" value="Secreto" class="ml-2" />
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Apenas membros podem ver o grupo)
                        </span>
                    </div>
                </div>
                <div class="mt-1 text-sm text-red-600">@error('privacy') {{ $message }} @enderror</div>
            </div>

            <!-- Imagem do grupo -->
            <div>
                <flux:label for="image" value="Imagem do Grupo" />
                <div class="mt-2 flex items-center">
                    @if ($image)
                        <div class="relative mr-4">
                            <img src="{{ $image->temporaryUrl() }}" class="w-24 h-24 rounded-lg object-cover">
                            <button type="button" wire:click="$set('image', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1">
                                <x-flux::icon icon="x-mark" class="w-4 h-4" />
                            </button>
                        </div>
                    @endif

                    <label for="image-upload" class="cursor-pointer bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center">
                        <x-flux::icon icon="camera" class="w-5 h-5 mr-2" />
                        <span>Escolher imagem</span>
                        <input id="image-upload" type="file" wire:model="image" class="hidden" accept="image/*">
                    </label>
                </div>
                <div class="mt-1 text-sm text-red-600">@error('image') {{ $message }} @enderror</div>
            </div>

            <!-- Imagem de capa do grupo -->
            <div>
                <flux:label for="coverImage" value="Imagem de Capa" />
                <div class="mt-2 flex items-center">
                    @if ($coverImage)
                        <div class="relative mr-4">
                            <img src="{{ $coverImage->temporaryUrl() }}" class="w-48 h-24 rounded-lg object-cover">
                            <button type="button" wire:click="$set('coverImage', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1">
                                <x-flux::icon icon="x-mark" class="w-4 h-4" />
                            </button>
                        </div>
                    @endif

                    <label for="cover-image-upload" class="cursor-pointer bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center">
                        <x-flux::icon icon="camera" class="w-5 h-5 mr-2" />
                        <span>Escolher imagem de capa</span>
                        <input id="cover-image-upload" type="file" wire:model="coverImage" class="hidden" accept="image/*">
                    </label>
                </div>
                <div class="mt-1 text-sm text-red-600">@error('coverImage') {{ $message }} @enderror</div>
            </div>

            <!-- Opções adicionais -->
            <div>
                <div class="flex items-center">
                    <flux:checkbox id="posts-require-approval" wire:model="postsRequireApproval" />
                    <flux:label for="posts-require-approval" value="Postagens precisam de aprovação" class="ml-2" />
                </div>
                <div class="mt-1 text-sm text-red-600">@error('postsRequireApproval') {{ $message }} @enderror</div>
            </div>

            <!-- Botões de ação -->
            <div class="flex justify-end space-x-3">
                <flux:button href="{{ route('grupos.index') }}" color="secondary">
                    Cancelar
                </flux:button>

                <flux:button type="submit" color="primary">
                    Criar Grupo
                </flux:button>
            </div>
        </form>
    </div>
</div>
