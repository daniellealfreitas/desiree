<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user-status {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user verification status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Usuário com email {$email} não encontrado.");
            return 1;
        }

        $this->info("👤 Usuário: {$user->name}");
        $this->info("📧 Email: {$user->email}");
        $this->info("🔑 Username: {$user->username}");
        $this->info("👥 Role: {$user->role}");
        $this->info("✅ Ativo: " . ($user->active ? 'Sim' : 'Não'));
        $this->info("📅 Criado em: " . $user->created_at->format('d/m/Y H:i:s'));
        
        if ($user->hasVerifiedEmail()) {
            $this->info("✅ Email verificado em: " . $user->email_verified_at->format('d/m/Y H:i:s'));
        } else {
            $this->warn("⏳ Email NÃO verificado");
        }

        return 0;
    }
}
