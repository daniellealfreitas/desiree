<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Notifications\CustomVerifyEmail;

class TestEmailVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-verification {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email verification functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if (!$email) {
            $email = $this->ask('Digite o email do usuário para testar');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Usuário com email {$email} não encontrado.");
            return 1;
        }

        $this->info("Testando envio de email de verificação para: {$user->name} ({$user->email})");

        try {
            // Test sending verification email
            $user->sendEmailVerificationNotification();
            
            $this->info("✅ Email de verificação enviado com sucesso!");
            $this->info("📧 Verifique os logs de email ou a caixa de entrada do usuário.");
            
            // Show admin users that will receive copies
            $adminUsers = User::where('role', 'admin')->get();
            if ($adminUsers->count() > 0) {
                $this->info("\n📋 Administradores que receberão cópias:");
                foreach ($adminUsers as $admin) {
                    $this->line("   • {$admin->name} ({$admin->email})");
                }
            }
            
            $this->info("📧 Cópia também enviada para: contato@swingcuritiba.com.br");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Erro ao enviar email: " . $e->getMessage());
            return 1;
        }
    }
}
