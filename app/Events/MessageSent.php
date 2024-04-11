<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversationId;
    public $message;
    public $receiver_photo;
    public $sender_photo;

    public function __construct($conversationId, $message, $sender_photo, $receiver_photo)
    {
        $this->conversationId = $conversationId;
        $this->message = $message;
        $this->sender_photo = $sender_photo;
        $this->receiver_photo = $receiver_photo;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->conversationId);
    }


    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => (int) $this->message->id,
                'conversation_id' => (int) $this->message->conversation_id,
                'sender_id' => (int) $this->message->sender_id,
                'content' => $this->message->content,
                'receiver_id' => (int) $this->message->receiver_id,
                'replay_on' => $this->message->replay_on,
                'attachment' => $this->message->attachment,
                'created_at' => $this->message->created_at,
                'updated_at' => $this->message->updated_at,
                'receiver_photo' => $this->receiver_photo,
                'sender_photo' => $this->sender_photo
            ],
        ];
    }
}
