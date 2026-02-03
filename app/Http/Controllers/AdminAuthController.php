<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Ticket;
use App\Models\ScannedTicket;

class AdminAuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('admin.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Demo credentials
        if ($request->username === 'admin' && $request->password === 'admin123') {
            Session::put('admin_logged_in', true);
            return redirect()->route('admin.validate');
        }

        return back()->withErrors(['login' => 'Invalid username or password']);
    }

    // Handle logout
    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login');
    }

    // Validate ticket (now part of this class)
    public function validateTicket(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'payment_method' => 'required'
        ]);

        $code = $request->code;
        $paymentMethod = $request->payment_method;

        $ticket = Ticket::where('ticket_code', $code)->first();

        if (!$ticket) {
            return back()->with('result', [
                'success' => false,
                'message' => 'Ticket not found!'
            ]);
        }

        if ($ticket->used) {
            return back()->with('result', [
                'success' => false,
                'message' => 'Ticket has already been scanned!',
                'ticketData' => $ticket->toArray(),
                'alreadyScanned' => true
            ]);
        }

        // Mark ticket as used and paid
        $ticket->update([
            'used' => true,
            'paid' => true,
            'payment_method' => $paymentMethod
        ]);

        // Log scanned ticket
        ScannedTicket::create([
            'ticket_id' => $ticket->id,
            'ticket_code' => $ticket->ticket_code
        ]);

        return back()->with('result', [
            'success' => true,
            'message' => 'Ticket validated successfully!',
            'ticketData' => $ticket->toArray()
        ]);
    }
}
