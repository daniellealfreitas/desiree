<?php

namespace App\Http\Controllers;

use App\Models\VipSubscription;
use App\Services\VipSubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends CashierWebhookController
{
    /**
     * Handle Stripe checkout session completed event.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleCheckoutSessionCompleted(array $payload)
    {
        $session = $payload['data']['object'];
        
        // Check if this is a VIP subscription payment
        if (isset($session['metadata']['subscription_id'])) {
            $subscriptionId = $session['metadata']['subscription_id'];
            
            // Find the subscription
            $subscription = VipSubscription::find($subscriptionId);
            
            if ($subscription) {
                // Update the subscription with payment details
                $subscription->update([
                    'stripe_payment_id' => $session['payment_intent'],
                ]);
                
                // Activate the subscription
                $vipSubscriptionService = app(VipSubscriptionService::class);
                $vipSubscriptionService->activateSubscription($subscription);
                
                Log::info('VIP subscription activated via webhook', [
                    'subscription_id' => $subscriptionId,
                    'user_id' => $subscription->user_id,
                    'plan_days' => $subscription->plan_days,
                ]);
            }
        }
        
        return new Response('Webhook Handled', 200);
    }
}
