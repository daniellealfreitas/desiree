<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VipSubscription;
use App\Services\VipSubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Carbon\Carbon;

class VipSubscriptionController extends Controller
{
    protected $vipSubscriptionService;

    public function __construct(VipSubscriptionService $vipSubscriptionService)
    {
        $this->vipSubscriptionService = $vipSubscriptionService;
    }

    /**
     * Create a checkout session for VIP subscription
     */
    public function createCheckoutSession(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|string|in:30,60,90,180,360',
            'price' => 'required|numeric',
        ]);

        $user = Auth::user();
        $planDays = (int) $validated['plan'];
        $price = (float) $validated['price'];

        // Create a new VIP subscription record with pending status
        $subscription = VipSubscription::create([
            'user_id' => $user->id,
            'plan_days' => $planDays,
            'amount' => $price,
            'status' => 'pending',
        ]);

        try {
            Stripe::setApiKey(config('cashier.secret'));
            
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'brl',
                        'product_data' => [
                            'name' => "Assinatura VIP {$planDays} dias",
                            'description' => "Assinatura VIP por {$planDays} dias",
                        ],
                        'unit_amount' => (int)($price * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('vip.payment.success', ['subscription' => $subscription->id]),
                'cancel_url' => route('vip.payment.cancel', ['subscription' => $subscription->id]),
                'customer_email' => $user->email,
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'user_id' => $user->id,
                    'plan_days' => $planDays,
                ],
            ]);
            
            // Update subscription with session ID
            $subscription->update([
                'stripe_session_id' => $session->id,
            ]);
            
            return redirect($session->url);
            
        } catch (ApiErrorException $e) {
            // Delete the subscription if payment creation fails
            $subscription->delete();
            
            return redirect()->route('renovar-vip')
                ->with('error', 'Ocorreu um erro ao processar o pagamento. Por favor, tente novamente.');
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request, VipSubscription $subscription)
    {
        // Verify that the subscription belongs to the current user
        if ($subscription->user_id != Auth::id()) {
            return redirect()->route('renovar-vip')
                ->with('error', 'Assinatura inválida.');
        }
        
        // Process the successful payment
        $this->vipSubscriptionService->activateSubscription($subscription);
        
        return redirect()->route('renovar-vip')
            ->with('success', 'Pagamento realizado com sucesso! Sua assinatura VIP foi ativada.');
    }
    
    /**
     * Handle cancelled payment
     */
    public function paymentCancel(Request $request, VipSubscription $subscription)
    {
        // Verify that the subscription belongs to the current user
        if ($subscription->user_id != Auth::id()) {
            return redirect()->route('renovar-vip')
                ->with('error', 'Assinatura inválida.');
        }
        
        // Mark the subscription as cancelled
        $subscription->update([
            'status' => 'cancelled',
        ]);
        
        return redirect()->route('renovar-vip')
            ->with('info', 'Pagamento cancelado. Você pode tentar novamente a qualquer momento.');
    }
}
