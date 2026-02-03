<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::create([
            'ticket_code' => 'TICKET:EVT001-1|NAME:John Doe|EMAIL:john@example.com|EVENT:Concert|DATE:2026-02-02',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'event' => 'Concert',
            'date' => '2026-02-02',
            'used' => false,
        ]);

        Ticket::create([
            'ticket_code' => 'TICKET:EVT002-1|NAME:Jane Smith|EMAIL:jane@example.com|EVENT:Festival|DATE:2026-02-10',
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'event' => 'Festival',
            'date' => '2026-02-10',
            'used' => false,
        ]);
    }
}
