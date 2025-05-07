<div>
    @if($errors->has('global'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 border border-red-400">
            {{ $errors->first('global') }}
        </div>
    @endif
     <flux:heading size="lg">Busca avançada</flux:heading>
     <flux:subheading>Customize your layout and notification preferences.</flux:subheading>
    <section id="searchform" class="p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg">
        <form wire:submit.prevent="search" class="space-y-6">
            {{-- Filtros básicos --}}
         <div class="grid grid-cols-4 gap-4">
            <flux:field>
                <flux:label for="id">ID</flux:label>
                <flux:input id="id" wire:model="filters.id" />
                <flux:error name="filters.id" />
            </flux:field>

            <flux:field>
                <flux:label for="username">Username</flux:label>
                <flux:input id="username" wire:model="filters.username" />
                <flux:error name="filters.username" />
            </flux:field>

            <flux:field>
                <flux:label for="anuncio">Anúncio</flux:label>
                <flux:input id="anuncio" wire:model="filters.anuncio" />
                <flux:error name="filters.anuncio" />
            </flux:field>
        </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label for="estado">Estado</flux:label>
                    <flux:select id="estado" wire:model.live="selectedState" placeholder="Selecione">
                        @foreach($states as $state)
                            <flux:select.option value="{{ $state->id }}">{{ $state->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="selectedState" />
                </flux:field>

                <flux:field>
                    <flux:label for="cidade">Cidade</flux:label>
                    <flux:select id="cidade" wire:model.live="selectedCity" placeholder="Selecione">
                        @foreach($cities as $city)
                            <flux:select.option value="{{ $city->id }}">{{ $city->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="selectedCity" />
                </flux:field>
            </div>


            {{-- Filtros visuais com Tailwind + Flux --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-inner">
                {{-- Coluna 1 --}}
                <div class="space-y-4">
                    <flux:radio.group label="Perfil com Foto?" wire:model="has_photo">
                        <flux:radio value="1" label="Sim" checked />
                        <flux:radio value="0" label="Não" />
                    </flux:radio.group>

                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Ordenar por:</label>
                    <flux:radio.group wire:model="sort_by">
                        <flux:radio value="id_asc" label="ID Crescente" />
                        <flux:radio value="id_desc" label="ID Decrescente" />
                        <flux:radio value="last_access" label="Último Acesso" checked />
                    </flux:radio.group>
                </div>

                {{-- Coluna 2 --}}
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Busco por:</label>
                    <div class="space-y-2">
                        <flux:checkbox wire:model="searching_for" value="casais" label="Casais" />
                        <flux:checkbox wire:model="searching_for" value="homens" label="Homens" />
                        <flux:checkbox wire:model="searching_for" value="mulheres" label="Mulheres" />
                        <flux:checkbox wire:model="real_profiles" label="Perfis Reais" checked />
                    </div>

                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Cadastrados há:</label>
                    <flux:radio.group wire:model="registered_since">
                        <flux:radio value="7" label="7 Dias" checked />
                        <flux:radio value="15" label="15 Dias" />
                        <flux:radio value="30" label="30 Dias" />
                        <flux:radio value="all" label="Todos" />
                    </flux:radio.group>
                </div>

                {{-- Coluna 3 --}}
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Que Buscam por:</label>
                    <div class="space-y-2">
                        <flux:checkbox wire:model="looking_for" value="casais" label="Casais" />
                        <flux:checkbox wire:model="looking_for" value="homens" label="Homens" checked />
                        <flux:checkbox wire:model="looking_for" value="mulheres" label="Mulheres" />
                    </div>
                </div>
            </div>

            {{-- Botão de busca --}}
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-red-500 text-white font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Buscar
                </button>
            </div>
        </form>
    </section>

    {{-- Resultados --}}
    @if($hasSearched)
        <section id="results" class="p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg mt-6">
            <h2 class="text-lg font-medium text-gray-300">Resultados</h2>
            <ul class="mt-4 space-y-2">
                @forelse($results as $result)
                    <li class="p-4 border border-neutral-200 dark:border-neutral-700 rounded-md">
                        <p><strong>ID:</strong> {{ $result->id }}</p>
                        <p><strong>Username:</strong> {{ $result->username }}</p>
                        <p><strong>Anúncio:</strong> {{ $result->anuncio }}</p>
                    </li>
                @empty
                    <li class="text-gray-500">Nenhum resultado encontrado.</li>
                @endforelse
            </ul>
        </section>
    @endif
</div>
