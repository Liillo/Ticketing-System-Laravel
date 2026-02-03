<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MpesaService;
use App\Models\Ticket;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // Show booking type selection
    public function bookingType()
    {
        return view('booking-type');
    }

    // Show individual booking form
    public function individualBooking()
    {
        return view('booking-individual');
    }

    // Submit individual booking
    public function submitIndividualBooking(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $attendees = [$data];
        $ticketPrice = 5000;
        $totalAmount = $ticketPrice;

        session([
            'bookingData' => [
                'bookingType' => 'individual',
                'attendees'   => $attendees,
                'ticketPrice' => $ticketPrice,
                'totalAmount' => $totalAmount,
            ]
        ]);

        return redirect()->route('booking.checkout');
    }

    // Show group booking form
    public function groupBooking()
    {
        return view('booking-group');
    }

    // Submit group booking
    public function submitGroupBooking(Request $request)
    {
        $rules = [];
        foreach ($request->attendees as $i => $attendee) {
            $rules["attendees.$i.name"]  = 'required|string|max:255';
            $rules["attendees.$i.email"] = 'required|email|max:255';
            $rules["attendees.$i.phone"] = 'required|string|max:20';
        }

        $data = $request->validate($rules);

        $ticketPrice = 5000;
        $totalAmount = count($data['attendees']) * $ticketPrice;

        session([
            'bookingData' => [
                'bookingType' => 'group',
                'attendees'   => $data['attendees'],
                'ticketPrice' => $ticketPrice,
                'totalAmount' => $totalAmount,
            ]
        ]);

        return redirect()->route('booking.checkout');
    }

    // Checkout page: confirm booking + select payment
    public function checkout()
    {
        $bookingData = session('bookingData');
        if (!$bookingData) {
            return redirect()->route('booking.type');
        }

        return view('checkout', compact('bookingData'));
    }

    // Process payment (STK push or other methods)
    public function processCheckout(Request $request, MpesaService $mpesa)
    {
        // Ensure session is started and has a valid ID
        if (!session()->isStarted()) {
            session()->start();
        }

        // Always regenerate if missing
        $sessionId = session()->getId();
        if (!$sessionId) {
            session()->regenerate();
            $sessionId = session()->getId();
        }

        // ✅ If you’re not using database sessions, generate your own booking reference
        // This avoids the NOT NULL error in tickets.session_id
        if (!config('session.driver') === 'database') {
            $sessionId = Str::uuid()->toString();
        }

        // Validate required request fields
        $request->validate([
            'phone' => 'required|string',
            'payment_method' => 'required|string'
        ]);

        $bookingData = session('bookingData');
        if (!$bookingData) {
            return redirect()->route('booking.type')->with('error', 'No booking data found.');
        }

        $attendees = $bookingData['attendees'];
        $tickets = [];

        // Generate tickets in DB but mark as unpaid
        foreach ($attendees as $i => $person) {
            $ticket = Ticket::create([
                'session_id'       => $sessionId, // ✅ always set
                'ticket_code'      => strtoupper(Str::random(6)) . '-' . ($i + 1),
                'name'             => $person['name'] ?? 'Unknown',
                'email'            => $person['email'] ?? 'unknown@example.com',
                'phone'            => $person['phone'] ?? '0000000000',
                'price'            => $bookingData['ticketPrice'] ?? 0,
                'used'             => false,
                'paid'             => false,
                'payment_method'   => null,
                'mpesa_checkout_id'=> null,
            ]);

            $tickets[] = $ticket;
        }

        // Store ticket IDs in session for retrieval later
        session(['ticket_ids' => collect($tickets)->pluck('id')->toArray()]);

        // Handle M-Pesa payment
        if ($request->payment_method === 'mpesa') {
            foreach ($tickets as $ticket) {
                $res = $mpesa->stkPush(
                    $request->phone,
                    $ticket->price,
                    $ticket->ticket_code,
                    'Ticket Payment'
                );

                // Save checkout request ID
                $ticket->update([
                    'mpesa_checkout_id' => $res['CheckoutRequestID'] ?? null
                ]);
            }

            return view('waiting-payment', ['tickets' => $tickets]);
        }

        // Other payment methods: mark tickets as paid immediately
        foreach ($tickets as $ticket) {
            $ticket->update([
                'paid' => true,
                'payment_method' => $request->payment_method
            ]);
        }

        return redirect()->route('booking.ticket');
    }

    // Show ticket page after payment
    public function ticket()
    {
        $ticketIds = session('ticket_ids');
        if (!$ticketIds) {
            return redirect()->route('booking.type')->with('error', 'No tickets found.');
        }

        $tickets = Ticket::whereIn('id', $ticketIds)->get();

        return view('ticket', compact('tickets'));
    }
}
