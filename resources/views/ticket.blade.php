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

    // Process payment and ticket creation
    public function processCheckout(Request $request, MpesaService $mpesa)
    {
        $request->validate([
            'phone' => 'required',
            'payment_method' => 'required'
        ]);

        $bookingData = session('bookingData');
        if (!$bookingData) {
            return redirect()->route('booking.type')->with('error', 'No booking data found.');
        }

        $attendees = $bookingData['attendees'] ?? [];
        if (empty($attendees)) {
            return redirect()->route('booking.type')->with('error', 'No attendees found.');
        }

        // Generate tickets
        $tickets = [];
        foreach ($attendees as $i => $person) {
            $tickets[] = Ticket::create([
                'session_id' => session()->getId(),
                'ticket_code' => Str::upper(Str::random(6)) . '-' . ($i+1),
                'name' => $person['name'],
                'email' => $person['email'],
                'phone' => $person['phone'],
                'price' => $bookingData['ticketPrice'],
                'used' => false,
                'paid' => false,
            ]);
        }

        // Store ticket IDs in session
        $ticketIds = collect($tickets)->pluck('id')->toArray();
        session(['ticket_ids' => $ticketIds]);

        // Handle M-Pesa payment
        if ($request->payment_method === 'mpesa') {
            foreach ($tickets as $ticket) {
                $res = $mpesa->stkPush(
                    $request->phone,
                    $ticket->price,
                    $ticket->ticket_code,
                    'Ticket Payment'
                );

                $ticket->update([
                    'mpesa_checkout_id' => $res['CheckoutRequestID'] ?? null,
                    'payment_method' => 'mpesa'
                ]);
            }

            // Show waiting-payment page
            return view('waiting-payment', compact('tickets'));
        }

        // Other payment method (cash, card, etc.)
        foreach ($tickets as $ticket) {
            $ticket->update([
                'paid' => true,
                'payment_method' => $request->payment_method
            ]);
        }

        return redirect()->route('booking.ticket');
    }

    // Show ticket page
    public function ticket()
    {
        $ticketIds = session('ticket_ids');
        if (!$ticketIds) {
            return redirect()->route('booking.type')->with('error', 'No tickets found.');
        }

        $tickets = Ticket::whereIn('id', $ticketIds)->get();
        if ($tickets->isEmpty()) {
            return redirect()->route('booking.type')->with('error', 'No tickets found.');
        }

        return view('ticket', compact('tickets'));
    }
}
