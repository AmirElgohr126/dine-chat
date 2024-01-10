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
            $userData['ghost_mood'] = (int) $this->reservation->users->ghost_mood;
            $userData['phone'] = (int) $this->reservation->users->phone;
            $userData['notification_status'] = (int) $this->reservation->users->notification_status;
            $userData['x'] = (float) $this->reservation->chairs->x;
            $userData['y'] = (float) $this->reservation->chairs->y;
            $userData['photo'] =  retriveMedia() . $userData['photo'];
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
