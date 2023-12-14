<?php

namespace App\Http\Resources\Restaurant;

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
            'table_id' => $this->table_id,
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
            'tables' => [
                'id' => $this->tables->id,
                'table_number' => $this->tables->table_number,
                'restaurant_id' => $this->tables->restaurant_id,
            ],
            'chairs' => [
                'id' => $this->chairs->id,
                'chair_number' => $this->chairs->chair_number,
                'table_id' => $this->chairs->table_id,
                'restaurant_id' => $this->chairs->restaurant_id,
            ],
        ];
    }
}
