<?php
namespace App\Http\Resources\V1\App\Notifications;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResources extends JsonResource
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
            'restaurant_name' => $this->restaurant_name,
            'message' => $this->message,
            'sent_at' => $this->sent_at,
            'media' => retriveMedia() . $this->photo
        ];
    }
}

