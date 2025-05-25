<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class QueueStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show queue status and statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("📊 Status da Fila de Emails");
        $this->info("=" . str_repeat("=", 40));
        
        // Jobs pendentes
        $pendingJobs = DB::table('jobs')->count();
        $this->info("⏳ Jobs Pendentes: {$pendingJobs}");
        
        // Jobs falhados
        $failedJobs = DB::table('failed_jobs')->count();
        if ($failedJobs > 0) {
            $this->warn("❌ Jobs Falhados: {$failedJobs}");
        } else {
            $this->info("✅ Jobs Falhados: {$failedJobs}");
        }
        
        // Últimos jobs
        $recentJobs = DB::table('jobs')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['payload', 'created_at']);
            
        if ($recentJobs->count() > 0) {
            $this->info("\n📋 Últimos Jobs na Fila:");
            foreach ($recentJobs as $job) {
                $payload = json_decode($job->payload, true);
                $jobName = $payload['displayName'] ?? 'Unknown';
                $this->line("  • {$jobName} - {$job->created_at}");
            }
        }
        
        // Jobs falhados recentes
        $recentFailedJobs = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->limit(3)
            ->get(['payload', 'exception', 'failed_at']);
            
        if ($recentFailedJobs->count() > 0) {
            $this->warn("\n❌ Jobs Falhados Recentes:");
            foreach ($recentFailedJobs as $job) {
                $payload = json_decode($job->payload, true);
                $jobName = $payload['displayName'] ?? 'Unknown';
                $this->line("  • {$jobName} - {$job->failed_at}");
            }
        }
        
        $this->info("\n🔧 Comandos Úteis:");
        $this->line("  php artisan queue:start     - Iniciar worker");
        $this->line("  php artisan queue:retry all - Reprocessar jobs falhados");
        $this->line("  php artisan queue:flush     - Limpar jobs falhados");
        
        return 0;
    }
}
