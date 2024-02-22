<?php

namespace App\Http\Resources\V2\DashboardAdmin\Packages;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo ? retriveMedia() . $this->photo : '',
            'description' => $this->description,
            'price_per_month' => $this->price,
            'price_per_year' => $this->price,
            'status' => $this->status,
            'period_finished_after' => $this->period_finished_after,
            'features' => $this->features,
            'limitations' => $this->limitations,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
