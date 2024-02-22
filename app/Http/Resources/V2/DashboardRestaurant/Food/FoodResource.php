<?php

namespace App\Http\Resources\V2\DashboardRestaurant\Food;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
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
            'images' => $this->images->image,
            'translations' => [
                    'name' => $this->translations->name,
                    'locale' => $this->translations->locale,
                ],


        ];
    }
}
