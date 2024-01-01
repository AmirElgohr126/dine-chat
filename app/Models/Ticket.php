<?php

namespace App\Models;

use App\Models\RestaurantUser;
use Illuminate\Database\Eloquent\Model;
use App\Models\MessagesRestaurantsSupport;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'image',
        'status',
        'restaurant_user_id',
        // Add other fields as necessary
    ];

    // Define the relationship with MessagesRestaurantsSupport
    public function messagesRestaurantsSupports()
    {
        return $this->hasMany(MessagesRestaurantsSupport::class, 'ticket_id');
    }

    // Define the relationship with RestaurantUser
    public function restaurantUser()
    {
        return $this->belongsTo(RestaurantUser::class, 'restaurant_user_id');
    }

    protected static function booted()
    {
        // Event for decrypting the content after a Post is retrieved
        static::retrieved(function ($ticket) {
            $ticket->image = retriveMedia() . $ticket->image;
        });
    }
}
