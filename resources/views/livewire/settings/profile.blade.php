<?php

use App\Models\User;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $username = '';
    public string $bio = '';
    public string $aniversario = '';
    public string $sexo = '';
    public string $role = '';
    public bool $privado = false;
    public $states = [];
    public $cities = [];
    public $selectedState = null;
    public $selectedCity = null;
    public string $latitude = "";
    public string $longitude = "";

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username ?? '';
        $this->bio = $user->bio ?? '';
        $this->aniversario = $user->aniversario ? $user->aniversario->format('Y-m-d') : '';
        $this->sexo = $user->sexo ?? '';
        $this->privado = $user->privado ?? false;
        $this->selectedState = $user->state_id;
        $this->selectedCity = $user->city_id;
        $this->role = $user->role ?? null;
        $this->latitude = $user->latitude ?? '';
        $this->longitude = $user->longitude ?? '';
        $this->states = State::orderBy('name', 'asc')->get();
        if ($this->selectedState) {
            $this->cities = City::where('state_id', $this->selectedState)->orderBy('name', 'asc')->get();
        }
    }

    public function updatedSelectedState($stateId): void
    {
        $this->cities = $stateId ? City::where('state_id', $stateId)->orderBy('name', 'asc')->get() : [];
        $this->selectedCity = null;

        // Atualiza o state_id do usuário
        $user = Auth::user();
        $user->state_id = $stateId;
        $user->city_id = null; // Limpa a cidade quando muda o estado
        $user->save();
    }

    public function updatedSelectedCity($cityId): void
    {
        // Atualiza o city_id do usuário
        $user = Auth::user();
        $user->city_id = $cityId;
        $user->save();
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'aniversario' => ['nullable', 'date'],
            'sexo' => ['nullable', 'string', 'in:Homem,Mulher,Casal'],
            'privado' => ['boolean'],
            'selectedState' => ['nullable', 'exists:states,id'],
            'selectedCity' => ['nullable', 'exists:cities,id'],
            'latitude' => ['nullable', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['nullable', 'numeric', 'min:-180', 'max:180'],
            // Adiciona validação para role apenas se for admin
            'role' => [Auth::user()->role === 'admin' ? 'required' : 'nullable', 'in:admin,vip,visitante'],
        ]);

        // Converter latitude e longitude para float
        if (isset($validated['latitude']) && $validated['latitude'] !== null) {
            $validated['latitude'] = (float) $validated['latitude'];
        }

        if (isset($validated['longitude']) && $validated['longitude'] !== null) {
            $validated['longitude'] = (float) $validated['longitude'];
        }

        $user->fill($validated);

        // Permite alterar o role apenas se for admin
        if (Auth::user()->role === 'admin' && isset($validated['role'])) {
            $user->role = $validated['role'];
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Perfil')" :subheading="__('Editar suas informações pessoais')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Nome')" type="text" required autofocus autocomplete="name" />

            <flux:input wire:model="username" :label="__('Nome de usuário')" type="text" required autocomplete="username" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Seu email não foi vericado.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Clique aqui para re-enviar o link de confirmação.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('Um novo link de verificação foi enviado para o seu email.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <flux:textarea wire:model="bio" :label="__('Sobre você')" rows="auto" />

            <flux:input wire:model="aniversario" :label="__('Data de Nascimento')" type="date" />

            <flux:select wire:model="sexo" :label="__('Gênero')">
                <option value="">Selecione...</option>
                <option value="Homem">Homem</option>
                <option value="Mulher">Mulher</option>
                <option value="Casal">Casal</option>
            </flux:select>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <flux:select wire:model.live="selectedState" :label="__('Estado')">
                        <option value="">{{ __('Selecione...') }}</option>
                        @foreach ($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:select wire:model.live="selectedCity" :label="__('Cidade')">
                        <option value="">{{ __('Selecione...') }}</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            @if(auth()->user() && auth()->user()->role === 'admin')
                <flux:select wire:model="role" :label="__('Papel do usuário')">
                    <option value="admin">Administrador</option>
                    <option value="vip">VIP</option>
                    <option value="visitante">Visitante</option>
                </flux:select>
            @else
                <div class="mb-4">
                    <flux:label>{{ __('Tipo de usuário') }}</flux:label>

                    <flux:input wire:model="role" readonly variant="filled" value="{{ auth()->user()->role }}"   />
                </div>
            @endif

            <flux:field variant="inline">
                <flux:label>{{ __('Perfil Privado') }}</flux:label>
                <flux:switch wire:model="privado" />
                <flux:error name="privado" />
            </flux:field>

            <flux:field :label="__('Localização')" help="Sua localização é atualizada automaticamente quando você permite o acesso no navegador.">
                <div class="grid grid-cols-2 gap-4">
                    <flux:input type="text" wire:model="latitude" :label="__('Latitude')" />
                    <flux:input type="text" wire:model="longitude" :label="__('Longitude')" />
                </div>

                <div class="flex items-center gap-2 mt-2">
                    <button type="button" id="get-location-btn" class="px-3 py-1 bg-blue-500 text-white rounded flex items-center">
                        <x-flux::icon icon="map-pin" class="w-4 h-4 mr-1" />
                        {{ __('Atualizar agora') }}
                    </button>
                    <span id="location-status" class="text-sm text-gray-500"></span>
                </div>

                <div class="mt-2 text-xs text-gray-500">
                    <p>Sua localização é usada para encontrar pessoas próximas no radar.</p>
                    <p>A localização é atualizada automaticamente quando você usa o aplicativo.</p>
                </div>
            </flux:field>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const getLocationBtn = document.getElementById('get-location-btn');
                    const locationStatus = document.getElementById('location-status');

                    // Função para obter a localização
                    function getLocation() {
                        if (!navigator.geolocation) {
                            locationStatus.textContent = 'Geolocalização não é suportada pelo seu navegador';
                            return;
                        }

                        locationStatus.textContent = 'Obtendo sua localização...';
                        locationStatus.className = 'text-sm text-blue-500';

                        navigator.geolocation.getCurrentPosition(
                            // Success callback
                            function(position) {
                                // Garantir que os valores são numéricos
                                const latitude = parseFloat(position.coords.latitude);
                                const longitude = parseFloat(position.coords.longitude);

                                // Validar se os valores são válidos
                                if (isNaN(latitude) || isNaN(longitude)) {
                                    locationStatus.textContent = 'Coordenadas inválidas recebidas do navegador';
                                    locationStatus.className = 'text-sm text-red-500';
                                    return;
                                }

                                // Validar se os valores estão dentro de limites razoáveis
                                if (latitude < -90 || latitude > 90 || longitude < -180 || longitude > 180) {
                                    locationStatus.textContent = 'Coordenadas fora dos limites válidos';
                                    locationStatus.className = 'text-sm text-red-500';
                                    return;
                                }

                                // Use Livewire to update the component properties
                                @this.set('latitude', latitude.toFixed(7));
                                @this.set('longitude', longitude.toFixed(7));

                                locationStatus.textContent = 'Localização obtida com sucesso!';
                                locationStatus.className = 'text-sm text-green-500';

                                // Salvar as coordenadas no localStorage para uso pelo script de geolocalização automática
                                localStorage.setItem('userCoords', JSON.stringify({
                                    latitude: parseFloat(latitude),
                                    longitude: parseFloat(longitude),
                                    timestamp: Date.now()
                                }));
                            },
                            // Error callback
                            function(error) {
                                let errorMessage = 'Erro ao obter localização';

                                switch(error.code) {
                                    case error.PERMISSION_DENIED:
                                        errorMessage = 'Permissão de localização negada';
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        errorMessage = 'Informação de localização indisponível';
                                        break;
                                    case error.TIMEOUT:
                                        errorMessage = 'Tempo esgotado ao obter localização';
                                        break;
                                }

                                locationStatus.textContent = errorMessage;
                                locationStatus.className = 'text-sm text-red-500';
                            },
                            // Options
                            {
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 0
                            }
                        );
                    }

                    // Adicionar evento de clique ao botão
                    getLocationBtn.addEventListener('click', getLocation);

                    // Verificar se já temos coordenadas salvas
                    if (!@this.latitude || !@this.longitude) {
                        // Se não temos coordenadas, tentar obter automaticamente
                        getLocation();
                    }
                });
            </script>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Salvar') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Salvo.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
