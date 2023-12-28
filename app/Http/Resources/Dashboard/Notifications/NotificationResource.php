<?php

namespace App\Http\Resources\Dashboard\Notifications;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'restaurant_id' => $this->restaurant_id,
            'title' => $this->title,
            'message' => $this->message,
            'last_sent_at' => $this->last_sent_at,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sent_at' => $this->sent_at,
            'photo' => retriveMedia().$this->photo
        ];
    }
}
