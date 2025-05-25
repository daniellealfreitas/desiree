<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUnverifiedUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:unverified-user {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an unverified user for testing email verification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("❌ Usuário com email {$email} já existe.");
            return 1;
        }
        
        // Create unverified user
        $user = User::create([
            'name' => 'Teste Verificação',
            'username' => 'teste_verif_' . rand(100, 999),
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => 'visitante',
            'active' => true,
            'email_verified_at' => null, // Não verificado
        ]);
        
        $this->info("✅ Usuário não verificado criado com sucesso!");
        $this->info("📧 Email: {$user->email}");
        $this->info("🔑 Senha: password");
        $this->info("🔗 Status: NÃO VERIFICADO");
        
        $this->info("\n🧪 Para testar:");
        $this->info("1. Faça login com: {$user->email} / password");
        $this->info("2. Tente acessar /dashboard - será redirecionado para verificação");
        $this->info("3. Clique em 'Reenviar email' na página de verificação");
        $this->info("4. Verifique sua caixa de entrada");
        
        return 0;
    }
}
