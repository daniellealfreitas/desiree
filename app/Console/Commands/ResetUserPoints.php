<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserPoint;
use Carbon\Carbon;

class ResetUserPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'points:reset {period=daily : The period to reset (daily, weekly, monthly)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset user points for the specified period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $period = $this->argument('period');
        
        if (!in_array($period, ['daily', 'weekly', 'monthly'])) {
            $this->error('Invalid period. Use daily, weekly, or monthly.');
            return 1;
        }
        
        $count = UserPoint::resetPoints($period);
        
        $this->info("Successfully reset {$period} points for {$count} users.");
        
        return 0;
    }
}
