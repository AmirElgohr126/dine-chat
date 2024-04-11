<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeletePublicPlaceReservation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $public_place_id;

    public int $user_id;


    /**
     * Create a new event instance.
     */
    public function __construct( int $public_place_id, int $user_id)
    {
        $this->public_place_id = $public_place_id;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('public_place.'.$this->public_place_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user_id
        ];
    }
}
