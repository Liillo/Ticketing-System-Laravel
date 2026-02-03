<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MpesaService;

class MpesaController extends Controller
{
    public function stkPush(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'amount' => 'required|numeric|min:1',
        ]);

        // TODO: Call Safaricom Daraja here
        return response()->json([
            'status' => true,
            'message' => 'STK Push request sent to ' . $request->phone
        ]);
    }
}

class PaymentController extends Controller
{
    public function pay(Request $request, MpesaService $mpesa)
    {
        $phone = $request->input('phone');
        $amount = $request->input('amount', 1);

        $response = $mpesa->stkPush($phone, $amount);

        return response()->json($response);
    }
}
