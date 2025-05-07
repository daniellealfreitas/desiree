<?php

namespace App\Services;

use App\Models\User;
use App\Models\VipSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VipSubscriptionService
{
    /**
     * Activate a VIP subscription for a user
     *
     * @param VipSubscription $subscription
     * @return bool
     */
    public function activateSubscription(VipSubscription $subscription): bool
    {
        try {
            // Get the user
            $user = $subscription->user;
            
            // Calculate the expiration date
            $expiresAt = Carbon::now()->addDays($subscription->plan_days);
            
            // Update the subscription
            $subscription->update([
                'status' => 'active',
                'activated_at' => Carbon::now(),
                'expires_at' => $expiresAt,
            ]);
            
            // Update the user role to VIP
            $user->update([
                'role' => 'vip',
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to activate VIP subscription: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check for expired VIP subscriptions and downgrade users
     *
     * @return int Number of users downgraded
     */
    public function checkExpiredSubscriptions(): int
    {
        $count = 0;
        
        // Find active subscriptions that have expired
        $expiredSubscriptions = VipSubscription::where('status', 'active')
            ->where('expires_at', '<', Carbon::now())
            ->get();
        
        foreach ($expiredSubscriptions as $subscription) {
            // Mark the subscription as expired
            $subscription->update([
                'status' => 'expired',
            ]);
            
            // Check if the user has any other active subscriptions
            $hasActiveSubscription = VipSubscription::where('user_id', $subscription->user_id)
                ->where('status', 'active')
                ->exists();
            
            // If no active subscriptions, downgrade the user
            if (!$hasActiveSubscription) {
                $user = User::find($subscription->user_id);
                
                if ($user && $user->role === 'vip') {
                    $user->update([
                        'role' => 'visitante',
                    ]);
                    
                    $count++;
                }
            }
        }
        
        return $count;
    }
    
    /**
     * Extend an existing VIP subscription
     *
     * @param User $user
     * @param int $days
     * @return bool
     */
    public function extendSubscription(User $user, int $days): bool
    {
        try {
            // Find the latest active subscription
            $activeSubscription = VipSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->orderBy('expires_at', 'desc')
                ->first();
            
            if ($activeSubscription) {
                // Extend the existing subscription
                $newExpiryDate = Carbon::parse($activeSubscription->expires_at)->addDays($days);
                
                $activeSubscription->update([
                    'expires_at' => $newExpiryDate,
                ]);
            } else {
                // Create a new subscription
                $expiresAt = Carbon::now()->addDays($days);
                
                VipSubscription::create([
                    'user_id' => $user->id,
                    'plan_days' => $days,
                    'status' => 'active',
                    'activated_at' => Carbon::now(),
                    'expires_at' => $expiresAt,
                ]);
                
                // Update the user role to VIP
                $user->update([
                    'role' => 'vip',
                ]);
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to extend VIP subscription: ' . $e->getMessage());
            return false;
        }
    }
}
