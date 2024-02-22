<?php

namespace App\Http\Resources\V2\App\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAttendanceResource extends JsonResource
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
            'chair_id' => $this->chair_id,
            'user_id' => $this->user_id,
            'users' => [
                'id' => $this->users->id,
                'first_name' => $this->users->first_name,
                'last_name' => $this->users->last_name,
                'email' => $this->users->email,
                'photo' => retriveMedia().$this->users->photo,
                'phone' => $this->users->phone,
                'ghost_mood' => $this->users->ghost_mood,
                'bio' => $this->users->bio,
            ],
            'chairs' => [
                'restaurant_id' => $this->chairs->restaurant_id,
                'id' => $this->chairs->id,
                'x' => $this->chairs->x,
                'y' => $this->chairs->y,
                'key' => $this->chairs->key,
                'img' => $this->chairs->img,
                'nfc_number' => $this->chairs->nfc_number,
                'name' => $this->chairs->name,
            ],
        ];
    }
}
