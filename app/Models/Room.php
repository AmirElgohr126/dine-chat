<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
        'restaurant_id',
        'type_room'
    ];



    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id')->select(
            [
                'id',
                'first_name',
                'last_name',
                'photo'
            ]
        );
    }


    
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id')->select(
            [
                'id',
                'first_name',
                'last_name',
                'photo'
            ]
        );
    }
}
