<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScannedTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'ticket_code'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
