<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Reset pontos diários à meia-noite
        $schedule->command('points:reset daily')->dailyAt('00:00');

        // Reset pontos semanais toda segunda-feira à meia-noite
        $schedule->command('points:reset weekly')->weeklyOn(1, '00:00');

        // Reset pontos mensais no primeiro dia do mês à meia-noite
        $schedule->command('points:reset monthly')->monthlyOn(1, '00:00');

        // Verificar assinaturas VIP expiradas diariamente à 1h da manhã
        $schedule->command('vip:check-subscriptions')->dailyAt('01:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
