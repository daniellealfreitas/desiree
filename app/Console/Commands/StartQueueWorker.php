<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartQueueWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start queue worker for email processing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🚀 Iniciando worker de fila para emails...");
        $this->info("📧 Processando emails em background");
        $this->info("⏹️  Use Ctrl+C para parar");
        
        // Executar o worker com configurações otimizadas
        $this->call('queue:work', [
            '--tries' => 3,           // Tentar 3 vezes se falhar
            '--timeout' => 60,        // Timeout de 60 segundos
            '--sleep' => 3,           // Aguardar 3 segundos entre jobs
            '--max-jobs' => 100,      // Processar no máximo 100 jobs antes de reiniciar
            '--max-time' => 3600,     // Rodar por no máximo 1 hora
        ]);
        
        return 0;
    }
}
