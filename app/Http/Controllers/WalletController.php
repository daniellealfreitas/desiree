<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class WalletController extends Controller
{
    /**
     * Display the wallet dashboard
     */
    public function index()
    {
        $wallet = Auth::user()->wallet;
        $transactions = WalletTransaction::where('user_id', Auth::id())
            ->with(['sourceUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('wallet.index', compact('wallet', 'transactions'));
    }

    /**
     * Show the form for adding funds
     */
    public function showAddFunds()
    {
        return view('wallet.add-funds');
    }

    /**
     * Process adding funds via Stripe
     */
    public function processAddFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $amount = $request->amount;
        $user = Auth::user();

        try {
            Stripe::setApiKey(config('cashier.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'brl',
                        'product_data' => [
                            'name' => "Adicionar Fundos à Carteira",
                            'description' => "Adicionar R$ " . number_format($amount, 2, ',', '.') . " à sua carteira",
                        ],
                        'unit_amount' => (int)($amount * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('wallet.add-funds.success', ['amount' => $amount]),
                'cancel_url' => route('wallet.add-funds.cancel'),
                'customer_email' => $user->email,
                'metadata' => [
                    'user_id' => $user->id,
                    'type' => 'wallet_deposit',
                ],
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao processar pagamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle successful fund addition
     */
    public function addFundsSuccess(Request $request)
    {
        $amount = $request->amount;
        $user = Auth::user();
        $wallet = $user->wallet;

        // Add funds to wallet
        $wallet->addFunds(
            $amount,
            'deposit',
            'Depósito via Stripe'
        );

        return redirect()->route('wallet.index')->with('success', 'Fundos adicionados com sucesso!');
    }

    /**
     * Handle cancelled fund addition
     */
    public function addFundsCancel()
    {
        return redirect()->route('wallet.index')->with('error', 'Adição de fundos cancelada.');
    }

    /**
     * Show the form for transferring funds
     */
    public function showTransferFunds()
    {
        return view('wallet.transfer');
    }

    /**
     * Process transferring funds
     */
    public function processTransferFunds(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $sender = Auth::user();
        $recipient = User::where('username', $request->username)->first();
        $amount = $request->amount;
        $description = $request->description;

        // Check if user is trying to transfer to themselves
        if ($sender->id === $recipient->id) {
            return back()->withErrors(['username' => 'Você não pode transferir para si mesmo.']);
        }

        // Check if sender has enough funds
        if ($sender->wallet->balance < $amount) {
            return back()->withErrors(['amount' => 'Saldo insuficiente para esta transferência.']);
        }

        // Process transfer
        $result = $sender->wallet->transferTo($recipient, $amount, $description);

        if ($result) {
            // Create notification for recipient
            $recipient->notifications()->create([
                'sender_id' => $sender->id,
                'type' => 'wallet_transfer',
                'message' => "{$sender->name} transferiu R$ " . number_format($amount, 2, ',', '.') . " para você.",
                'read' => false,
            ]);

            return redirect()->route('wallet.index')->with('success', 'Transferência realizada com sucesso!');
        } else {
            return back()->withErrors(['error' => 'Erro ao processar a transferência.']);
        }
    }

    /**
     * Show the form for withdrawing funds
     */
    public function showWithdrawFunds()
    {
        return view('wallet.withdraw');
    }

    /**
     * Process withdrawing funds
     */
    public function processWithdrawFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50',
            'pix_key' => 'required|string',
            'pix_key_type' => 'required|in:cpf,cnpj,email,phone,random',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        // Check if user has enough funds
        if ($user->wallet->balance < $amount) {
            return back()->withErrors(['amount' => 'Saldo insuficiente para este saque.']);
        }

        // Process withdrawal (create pending transaction)
        $transaction = $user->wallet->subtractFunds(
            $amount,
            'withdrawal',
            'Saque via PIX: ' . $request->pix_key_type . ' - ' . $request->pix_key
        );

        if ($transaction) {
            // In a real application, you would process the PIX transfer here
            // For now, we'll just create a pending transaction

            return redirect()->route('wallet.index')->with('success', 'Solicitação de saque enviada com sucesso! Seu saque será processado em até 24 horas.');
        } else {
            return back()->withErrors(['error' => 'Erro ao processar o saque.']);
        }
    }
}
