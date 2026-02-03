<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'session_id',
        'ticket_code',
        'name',
        'email',
        'phone',
        'price',
        'used',
        'paid',
        'payment_method',
        'mpesa_checkout_id',
    ];
}
