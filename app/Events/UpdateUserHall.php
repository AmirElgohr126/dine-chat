<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateUserHall implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reservation;
    public $restaurantId;

    public function __construct($reservation,$restaurantId)
    {
        $this->reservation = $reservation;
        $this->restaurantId = $restaurantId;
    }

    public function customizeUser()
    {
            $userData = $this->reservation->users->toArray();
            $userData['x'] = $this->reservation->chairs->x;
            $userData['y'] = $this->reservation->chairs->y;
            $userData['photo'] = retriveMedia() . $userData['photo'];
            return $userData;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('restaurant.' . $this->restaurantId);
    }


    public function broadcastWith()
    {
        $data = $this->customizeUser();
        return [
            'user' => $data
        ];
    }
}
