<?php

namespace App\Console\Commands;

use App\Services\VipSubscriptionService;
use Illuminate\Console\Command;

class CheckVipSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vip:check-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired VIP subscriptions and downgrade users';

    /**
     * Execute the console command.
     */
    public function handle(VipSubscriptionService $vipSubscriptionService)
    {
        $this->info('Checking for expired VIP subscriptions...');
        
        $count = $vipSubscriptionService->checkExpiredSubscriptions();
        
        $this->info("Downgraded {$count} users with expired VIP subscriptions.");
        
        return Command::SUCCESS;
    }
}
