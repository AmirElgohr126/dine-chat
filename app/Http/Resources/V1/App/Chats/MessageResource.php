<?php

namespace App\Http\Resources\V1\App\Chats;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'conversation_id' => $this->conversation_id,
            'sender_id' => $this->sender_id,
            'content' => $this->content,
            'attachment' => $this->attachment,
            'receiver_id' => $this->receiver_id,
            'replay_on' => $this->replay_on,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
