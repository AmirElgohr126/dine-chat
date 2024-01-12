<?php

namespace App\Http\Resources\Food;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodRatingResource extends JsonResource
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
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'details' => $this->details,
            'status' => $this->status,
            'name' => $this->name,
            'image' => $this->images, // Make sure $this->images is formatted correctly
            'rating' => $this->userRating->rating ?? 0, // Assumes 'userRating' is a relation
        ];
    }
}
