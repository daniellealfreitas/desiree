<?php
use Illuminate\Support\Facades\Storage;
?>

<div>
    @if($errors->has('global'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 border border-red-400">
            {{ $errors->first('global') }}
        </div>
    @endif
     <flux:heading size="lg">Busca avançada</flux:heading>
     <flux:subheading>Encontre outros usuários com base em seus critérios.</flux:subheading>
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
                    <flux:radio.group label="Perfil com Foto?" wire:model="filters.foto">
                        <flux:radio value="1" label="Sim" checked />
                        <flux:radio value="0" label="Não" />
                    </flux:radio.group>

                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Ordenar por:</label>
                    <flux:radio.group wire:model="filters.ordenar">
                        <flux:radio value="id_crescente" label="ID Crescente" />
                        <flux:radio value="id_decrescente" label="ID Decrescente" />
                        <flux:radio value="last_access" label="Último Acesso" checked />
                    </flux:radio.group>

                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Sexo:</label>
                    <div class="space-y-2">
                        <flux:checkbox wire:model="filters.sexo" value="casal" label="Casal" />
                        <flux:checkbox wire:model="filters.sexo" value="homem" label="Homem" />
                        <flux:checkbox wire:model="filters.sexo" value="mulher" label="Mulher" />
                    </div>
                </div>

                {{-- Coluna 2 --}}
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Busco por:</label>
                    <div class="space-y-2">
                        @foreach($procuras as $procura)
                            <flux:checkbox wire:model="filters.busco" value="{{ $procura->nome }}" label="{{ $procura->nome }}" />
                        @endforeach
                        <flux:checkbox wire:model="real_profiles" label="Perfis Reais" checked />
                    </div>

                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Cadastrados há:</label>
                    <flux:radio.group wire:model="filters.cadastrados">
                        <flux:radio value="7_dias" label="7 Dias" checked />
                        <flux:radio value="15_dias" label="15 Dias" />
                        <flux:radio value="30_dias" label="30 Dias" />
                        <flux:radio value="all" label="Todos" />
                    </flux:radio.group>
                </div>

                {{-- Coluna 3 --}}
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Que Buscam por:</label>
                    <div class="space-y-2">
                        @foreach($procuras as $procura)
                            <flux:checkbox wire:model="filters.que_buscam" value="{{ $procura->nome }}" label="{{ $procura->nome }}" />
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Botão de busca --}}
            <div class="flex justify-end items-center gap-3">
                <div wire:loading wire:target="search" class="text-sm text-gray-500 flex items-center">
                    <x-flux::icon name="arrow-path" class="w-4 h-4 mr-2 animate-spin" />
                    Buscando...
                </div>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <span class="flex items-center">
                        <x-flux::icon name="magnifying-glass" class="w-4 h-4 mr-2" />
                        Buscar
                    </span>
                </button>
            </div>
        </form>
    </section>

    {{-- Resultados --}}
    @if($hasSearched)
        <section id="results" class="p-6 border border-neutral-200 dark:border-neutral-700 shadow-md rounded-lg mt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-300">Resultados</h2>
                <p class="text-sm text-gray-500">{{ count($results) }} {{ count($results) == 1 ? 'usuário encontrado' : 'usuários encontrados' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($results as $result)
                    <div class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
                        {{-- Cover photo --}}
                        <div class="relative h-32 bg-cover bg-center rounded-t-lg"
                            style="background-image: url('{{
                                $result->userCoverPhotos->first()
                                ? Storage::url($result->userCoverPhotos->first()->cropped_photo_path ?? $result->userCoverPhotos->first()->photo_path)
                                : asset('images/users/capa.jpg')
                            }}'); background-size: cover; background-position: center;">
                        </div>

                        {{-- User info --}}
                        <div class="relative z-10 -mt-12 flex flex-col items-center">
                            {{-- Avatar with status indicator --}}
                            <div class="relative">
                                <img src="{{
                                    $result->userPhotos->first()
                                    ? Storage::url($result->userPhotos->first()->photo_path)
                                    : asset('images/users/avatar.jpg')
                                }}"
                                alt="Foto de Perfil" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                                <livewire:user-status-indicator :userId="$result->id" />
                            </div>

                            {{-- Name and username --}}
                            <h2 class="text-xl font-semibold mt-2">{{ $result->name }}</h2>
                            <p class="text-gray-600">
                                @if($result->username)
                                    <a href="{{ route('user.profile', ['username' => $result->username]) }}" class="hover:underline">
                                        {{ '@' . $result->username }}
                                    </a>
                                @else
                                    <span>@sem-username</span>
                                @endif
                            </p>

                            {{-- User stats --}}
                            <div class="mt-4 flex justify-around w-full">
                                <div class="text-center">
                                    <p class="text-lg font-semibold">{{ count($result->posts) }}</p>
                                    <p class="text-gray-500">Posts</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-semibold">{{ count($result->following) }}</p>
                                    <p class="text-gray-500">Seguindo</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-semibold">{{ count($result->followers) }}</p>
                                    <p class="text-gray-500">Seguidores</p>
                                </div>
                            </div>

                            {{-- Additional info --}}
                            @if($result->anuncio)
                                <div class="mt-3 px-4 w-full">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">{{ $result->anuncio }}</p>
                                </div>
                            @endif

                            {{-- Location info if available --}}
                            @if($result->city || $result->state)
                                <div class="mt-2 flex items-center justify-center text-sm text-gray-500">
                                    <x-flux::icon name="map-pin" class="w-4 h-4 mr-1" />
                                    {{ $result->city?->name ?? '' }} {{ $result->city && $result->state ? ',' : '' }} {{ $result->state?->name ?? '' }}
                                </div>
                            @endif

                            {{-- Follow button --}}
                            @if(auth()->check() && auth()->id() !== $result->id && $result->username)
                                <div class="mt-3">
                                    <a href="{{ route('user.profile', ['username' => $result->username]) }}"
                                       class="inline-flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Ver perfil
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <x-flux::icon name="face-frown" class="w-16 h-16 mx-auto text-gray-400 mb-4" />
                        <p class="text-lg text-gray-500 mb-2">Nenhum resultado encontrado.</p>
                        <p class="text-sm text-gray-400">Tente ajustar seus critérios de busca para encontrar mais usuários.</p>
                    </div>
                @endforelse
            </div>
        </section>
    @endif
</div>
