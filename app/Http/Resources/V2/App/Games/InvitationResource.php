<?php

namespace App\Http\Resources\V2\App\Games;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type_room' => $this->type_room,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'sender' => [
                'id' => $this->sender->id,
                'first_name' => $this->sender->first_name,
                'last_name' => $this->sender->last_name,
                'photo' => retriveMedia(). $this->sender->photo,
            ],
            'receiver' => [
                'id' => $this->receiver->id,
                'first_name' => $this->receiver->first_name,
                'last_name' => $this->receiver->last_name,
                'photo' => retriveMedia(). $this->receiver->photo,
            ],
            'restaurant_id' => $this->restaurant_id,
        ];
    }
}
