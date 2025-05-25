<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user for email verification testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'test@example.com';
        
        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->info("Usuário de teste já existe: {$email}");
            $user = User::where('email', $email)->first();
        } else {
            // Create test user
            $user = User::create([
                'name' => 'Usuário Teste',
                'username' => 'teste_' . rand(100, 999),
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'visitante',
                'active' => true,
            ]);
            
            $this->info("✅ Usuário de teste criado com sucesso!");
        }
        
        $this->info("📧 Email: {$user->email}");
        $this->info("🔑 Senha: password");
        $this->info("🔗 Status de verificação: " . ($user->hasVerifiedEmail() ? 'Verificado' : 'Não verificado'));
        
        if (!$user->hasVerifiedEmail()) {
            $this->info("\n🚀 Para testar a verificação de email:");
            $this->info("1. Acesse: https://desiree2.test/login");
            $this->info("2. Faça login com: {$user->email} / password");
            $this->info("3. Você será redirecionado para: https://desiree2.test/verify-email");
        }
        
        return 0;
    }
}
