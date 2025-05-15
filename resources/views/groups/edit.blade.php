<x-layouts.app :title="__('Editar Grupo')">
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Editar Grupo</h2>

        <form action="{{ route('grupos.update', $group) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nome do grupo -->
            <div>
                <flux:label for="name" value="Nome do Grupo" />
                <flux:input id="name" type="text" name="name" value="{{ old('name', $group->name) }}" class="w-full" required autofocus />
                <div class="mt-1 text-sm text-red-600">@error('name') {{ $message }} @enderror</div>
            </div>

            <!-- Descrição do grupo -->
            <div>
                <flux:label for="description" value="Descrição" />
                <flux:textarea id="description" name="description" class="w-full" rows="4">{{ old('description', $group->description) }}</flux:textarea>
                <div class="mt-1 text-sm text-red-600">@error('description') {{ $message }} @enderror</div>
            </div>

            <!-- Privacidade do grupo -->
            <div>
                <flux:label value="Privacidade" />
                <div class="mt-2 space-y-2">
                    <div class="flex items-center">
                        <input type="radio" id="privacy-public" name="privacy" value="public" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('privacy', $group->privacy) === 'public' ? 'checked' : '' }} />
                        <label for="privacy-public" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Público</label>
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Qualquer pessoa pode ver e entrar no grupo)
                        </span>
                    </div>

                    <div class="flex items-center">
                        <input type="radio" id="privacy-private" name="privacy" value="private" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('privacy', $group->privacy) === 'private' ? 'checked' : '' }} />
                        <label for="privacy-private" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Privado</label>
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            (Qualquer pessoa pode ver, mas precisa solicitar para entrar)
                        </span>
                    </div>

                    <div class="flex items-center">
                        <input type="radio" id="privacy-secret" name="privacy" value="secret" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('privacy', $group->privacy) === 'secret' ? 'checked' : '' }} />
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
                <flux:label for="image" value="Imagem do Grupo" />
                <div class="mt-2 flex items-center">
                    @if($group->image)
                        <div class="relative mr-4">
                            <img src="{{ asset('storage/' . $group->image) }}" class="w-24 h-24 rounded-lg object-cover">
                        </div>
                    @endif

                    <label for="image-upload" class="cursor-pointer bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center">
                        <x-flux::icon icon="camera" class="w-5 h-5 mr-2" />
                        <span>{{ $group->image ? 'Alterar imagem' : 'Escolher imagem' }}</span>
                        <input id="image-upload" type="file" name="image" class="hidden" accept="image/*">
                    </label>
                </div>
                <div class="mt-1 text-sm text-red-600">@error('image') {{ $message }} @enderror</div>
            </div>

            <!-- Imagem de capa do grupo -->
            <div>
                <flux:label for="cover_image" value="Imagem de Capa" />
                <div class="mt-2 flex items-center">
                    @if($group->cover_image)
                        <div class="relative mr-4">
                            <img src="{{ asset('storage/' . $group->cover_image) }}" class="w-48 h-24 rounded-lg object-cover">
                        </div>
                    @endif

                    <label for="cover-image-upload" class="cursor-pointer bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center">
                        <x-flux::icon icon="camera" class="w-5 h-5 mr-2" />
                        <span>{{ $group->cover_image ? 'Alterar imagem de capa' : 'Escolher imagem de capa' }}</span>
                        <input id="cover-image-upload" type="file" name="cover_image" class="hidden" accept="image/*">
                    </label>
                </div>
                <div class="mt-1 text-sm text-red-600">@error('cover_image') {{ $message }} @enderror</div>
            </div>

            <!-- Opções adicionais -->
            <div>
                <div class="flex items-center">
                    <input type="checkbox" id="posts_require_approval" name="posts_require_approval" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('posts_require_approval', $group->posts_require_approval) ? 'checked' : '' }} />
                    <label for="posts_require_approval" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Postagens precisam de aprovação</label>
                </div>
                <div class="mt-1 text-sm text-red-600">@error('posts_require_approval') {{ $message }} @enderror</div>
            </div>

            <!-- Botões de ação -->
            <div class="flex justify-end space-x-3">
                <flux:button href="{{ route('grupos.show', $group->slug) }}" color="secondary">
                    Cancelar
                </flux:button>

                <flux:button type="submit" color="primary">
                    Salvar Alterações
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
