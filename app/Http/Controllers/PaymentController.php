<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'user.userPhoto'])->get();
        return view('meus-pagamentos', compact('payments'));
    }
}
