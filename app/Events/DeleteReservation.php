<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeleteReservation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $restaurantId;
    public $userId;

    public function __construct($restaurantId,$userId)
    {
        $this->restaurantId = $restaurantId;
        $this->userId = $userId;
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->userId
        ];
    }


    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->restaurantId);
    }
}
