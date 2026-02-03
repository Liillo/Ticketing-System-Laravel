<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class AdminController extends Controller
{
    public function validatePage()
    {
        return view('admin.validate');
    }

    public function validateTicket(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $code = $request->code;

        $data = $this->parseTicketCode($code);

        if (!$data) {
            return back()->with('result', [
                'success' => false,
                'message' => 'Invalid ticket code format'
            ]);
        }

        $ticket = Ticket::where('ticket_code', $code)->first();

        if (!$ticket) {
            return back()->with('result', [
                'success' => false,
                'message' => 'Ticket not found!',
                'ticketData' => $data
            ]);
        }

        if ($ticket->used) {
            return back()->with('result', [
                'success' => false,
                'message' => 'This ticket has already been used!',
                'ticketData' => $ticket
            ]);
        }
            
        // Mark ticket as used
        $ticket->update(['used' => true]);

        return back()->with('result', [
            'success' => true,
            'message' => 'Ticket validated successfully!',
            'ticketData' => $ticket
        ]);
    }

    private function parseTicketCode($code)
    {
        try {
            $parts = explode('|', $code);

            $ticketPart = collect($parts)->first(fn($p) => str_starts_with($p, 'TICKET:'));
            $name = collect($parts)->first(fn($p) => str_starts_with($p, 'NAME:'));
            $email = collect($parts)->first(fn($p) => str_starts_with($p, 'EMAIL:'));
            $event = collect($parts)->first(fn($p) => str_starts_with($p, 'EVENT:'));
            $date = collect($parts)->first(fn($p) => str_starts_with($p, 'DATE:'));

            if (!$ticketPart || !$name || !$email || !$event || !$date) return null;

            $ticketPart = str_replace('TICKET:', '', $ticketPart);

            if (!preg_match('/^(.+)-(\d+)$/', $ticketPart, $m)) return null;

            return [
                'ticketId' => $m[1],
                'attendeeIndex' => (int)$m[2],
                'name' => str_replace('NAME:', '', $name),
                'email' => str_replace('EMAIL:', '', $email),
                'event' => str_replace('EVENT:', '', $event),
                'date' => str_replace('DATE:', '', $date),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
}
