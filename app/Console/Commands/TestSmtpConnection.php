<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestSmtpConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:smtp {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMTP connection and send a simple email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("🔧 Testando configuração SMTP...");
        $this->info("📧 Host: " . config('mail.mailers.smtp.host'));
        $this->info("🔌 Porta: " . config('mail.mailers.smtp.port'));
        $this->info("👤 Usuário: " . config('mail.mailers.smtp.username'));
        $this->info("🔐 Criptografia: " . config('mail.mailers.smtp.encryption'));
        $this->info("📤 De: " . config('mail.from.address'));
        
        $this->info("\n🚀 Enviando email de teste para: {$email}");
        
        try {
            Mail::raw('Este é um email de teste do sistema de verificação.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Teste SMTP - ' . config('app.name'))
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            $this->info("✅ Email enviado com sucesso!");
            $this->info("📧 Verifique a caixa de entrada de: {$email}");
            $this->info("📧 Verifique também a pasta de spam");
            
        } catch (\Exception $e) {
            $this->error("❌ Erro ao enviar email:");
            $this->error($e->getMessage());
            
            // Log do erro
            Log::error('Erro SMTP: ' . $e->getMessage());
            
            $this->info("\n🔍 Possíveis soluções:");
            $this->info("1. Verifique se o email naoresponda@swingcuritiba.com.br existe");
            $this->info("2. Verifique se a senha está correta");
            $this->info("3. Verifique se o servidor SMTP está acessível");
            $this->info("4. Verifique os logs em storage/logs/laravel.log");
            
            return 1;
        }
        
        return 0;
    }
}
