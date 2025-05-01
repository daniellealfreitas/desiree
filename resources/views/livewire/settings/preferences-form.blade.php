<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout heading="Preferências" subheading="Configure seus hobbies e o que você procura">
        <form wire:submit="updatePreferences" class="mt-6">
            <div class="space-y-6">
                {{-- Hobbies Section --}}
                <div>
                    <h3 class="text-lg font-medium">Seus Hobbies</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Selecione seus hobbies e interesses</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($hobbies as $hobby)
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <flux:checkbox 
                                    wire:model="selectedHobbies" 
                                    value="{{ $hobby->id }}"
                                />
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $hobby->nome }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Procuras Section --}}
                <div class="mt-8">
                    <h3 class="text-lg font-medium">O que você procura?</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Selecione o que você busca no app</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($procuras as $procura)
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <flux:checkbox 
                                    wire:model="selectedProcuras" 
                                    value="{{ $procura->id }}"
                                />
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $procura->nome }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <flux:button variant="primary" type="submit">{{ __('Salvar') }}</flux:button>

                    <x-action-message class="mr-3" on="preferences-updated">
                        {{ __('Salvo.') }}
                    </x-action-message>
                </div>
            </div>
        </form>
    </x-settings.layout>
</section>