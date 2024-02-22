<?php

namespace App\Http\Resources\V2\App\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
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
            'last_name'=> $this->last_name,
            'first_name'=> $this->first_name,
            'user_name'=> $this->user_name,
            'email'=> $this->email,
            'photo'=> retriveMedia().$this->photo,
            'bio' => $this->bio ?? '',
            'phone' => (string) $this->phone,
            'ghost_mood' => (int) $this->ghost_mood,
            'notification_status' => (int) $this->notification_status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
