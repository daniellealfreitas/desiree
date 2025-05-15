<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'sender'])->get();
        return view('meus-pagamentos', compact('payments'));
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Find the pending payment
        $payment = Payment::where('user_id', $userId)
            ->where('sender_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($payment) {
            // Update payment status
            $payment->update([
                'status' => 'completed',
                'payment_date' => now(), // Atualiza a data de pagamento para o momento da confirmação
            ]);

            // Add points to the receiver
            $pointsToAdd = (int)($payment->amount * 10); // 10 points per real

            UserPoint::create([
                'user_id' => $userId,
                'points' => $pointsToAdd,
                'action' => 'drink_received',
            ]);

            // Log the points
            UserPointLog::create([
                'user_id' => $userId,
                'points' => $pointsToAdd,
                'action' => 'Recebeu um drink de ' . Auth::user()->name,
                'total_points' => User::find($userId)->ranking_points + $pointsToAdd,
            ]);

            // Update user's ranking points
            $user->increment('ranking_points', $pointsToAdd);

            // Create notification for the receiver
            Notification::create([
                'user_id' => $userId,
                'from_user_id' => Auth::id(),
                'message' => Auth::user()->name . ' pagou um drink para você no valor de R$ ' . number_format($payment->amount, 2, ',', '.'),
                'type' => 'drink',
                'read' => false,
            ]);

            return redirect()->route('user.profile', $user->username)
                ->with('success', 'Pagamento realizado com sucesso! ' . $user->name . ' recebeu seu drink.');
        }

        return redirect()->route('user.profile', $user->username)
            ->with('info', 'Pagamento processado.');
    }

    /**
     * Handle cancelled payment
     */
    public function paymentCancel(Request $request)
    {
        // Find the pending payment
        $payment = Payment::where('sender_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($payment) {
            // Update payment status
            $payment->update([
                'status' => 'cancelled',
            ]);

            $user = User::find($payment->user_id);

            return redirect()->route('user.profile', $user->username)
                ->with('info', 'Pagamento cancelado. Você pode tentar novamente a qualquer momento.');
        }

        return redirect()->route('dashboard')
            ->with('info', 'Pagamento cancelado.');
    }
}
