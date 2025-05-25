<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $username = '';
    public string $role = 'visitante'; // Definir valor padrão como 'visitante'

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        try {
            // Definir regras de validação básicas
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
                'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            ];

            // Adicionar regra para 'role' se a coluna existir
            if (Schema::hasColumn('users', 'role')) {
                $rules['role'] = ['required', 'string', 'in:admin,visitante,vip'];
            }

            $validated = $this->validate($rules);

            $validated['password'] = Hash::make($validated['password']);

            // Garantir que o role seja 'visitante' para novos registros se a coluna existir
            if (Schema::hasColumn('users', 'role')) {
                $validated['role'] = 'visitante';
            }

            // Criar o usuário
            $user = User::create($validated);

            // Criar carteira para o usuário
            try {
                // Verificar se a tabela de wallets existe
                if (Schema::hasTable('wallets')) {
                    // Verificar se o usuário já tem uma wallet
                    if (!$user->wallet()->exists()) {
                        $user->wallet()->create([
                            'balance' => 0.00,
                            'active' => true,
                        ]);
                        \Illuminate\Support\Facades\Log::info('Wallet criada com sucesso para o usuário: ' . $user->id);
                    }
                } else {
                    \Illuminate\Support\Facades\Log::warning('Tabela de wallets não existe. Não foi possível criar wallet para o usuário: ' . $user->id);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Erro ao criar wallet: ' . $e->getMessage());
                // Não interromper o fluxo de registro por causa de erro na wallet
            }

            // Registrar o evento
            event(new Registered($user));

            // Fazer login para que o usuário possa acessar a página de verificação
            Auth::login($user);

            // Redirecionar para página de verificação de email com mensagem de sucesso
            session()->flash('status', 'registration-success');
            $this->redirect(route('verification.notice'), navigate: true);
        } catch (\Exception $e) {
            // Log do erro
            \Illuminate\Support\Facades\Log::error('Erro no registro: ' . $e->getMessage());

            // Adicionar erro ao formulário
            $this->addError('registration', 'Erro ao registrar: ' . $e->getMessage());
        }
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Criar conta')" :description="__('Prencha o formulário para criar sua conta')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <!-- Erros gerais -->
    @error('registration')
        <div class="text-red-500 text-sm text-center">{{ $message }}</div>
    @enderror

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Nome')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Nome Completo')"
        />
        <!-- Username -->
        <flux:input
            wire:model="username"
            :label="__('Nome de Usuário')"
            type="text"
            required
            autocomplete="username"
            :placeholder="__('Nome de Usuário')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email ')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Senha')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Senha')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirmar Senha')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirmar Senha')"
            viewable
        />

        @if(Schema::hasColumn('users', 'role'))
            <input wire:model="role" type="hidden" value="visitante" />
        @endif

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Criar Conta') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Já possui uma conta?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Entrar') }}</flux:link>
    </div>
</div>
