<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessagesRestaurantsSupport extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'message',
        'replay',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
