<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Auth\Notifications\VerifyEmail;

class TestVerificationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:verification-email {email} {--simple}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test verification email sending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $simple = $this->option('simple');
        
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Usuário com email {$email} não encontrado.");
            return 1;
        }

        $this->info("🧪 Testando envio de email de verificação...");
        $this->info("👤 Usuário: {$user->name} ({$user->email})");
        
        try {
            if ($simple) {
                $this->info("📧 Enviando notificação padrão do Laravel...");
                $user->notify(new VerifyEmail);
            } else {
                $this->info("📧 Enviando notificação customizada...");
                $user->notify(new CustomVerifyEmail);
            }
            
            $this->info("✅ Email enviado com sucesso!");
            $this->info("📧 Verifique a caixa de entrada de: {$user->email}");
            $this->info("📧 Verifique também a pasta de spam");
            
        } catch (\Exception $e) {
            $this->error("❌ Erro ao enviar email:");
            $this->error($e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}
