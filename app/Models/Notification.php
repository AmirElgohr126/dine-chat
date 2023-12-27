<?php

namespace App\Models;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;


    protected $fillable = [
        'restaurant_id',
        'title',
        'message',
        'last_sent_at',
        'status',
        'photo',
        'send_at',
    ];

    // Define the relationship with the Restaurant model
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
