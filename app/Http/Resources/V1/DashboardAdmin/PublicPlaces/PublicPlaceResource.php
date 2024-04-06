<?php

namespace App\Http\Resources\V1\DashboardAdmin\PublicPlaces;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicPlaceResource extends JsonResource
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
            'name' => $this->name,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'photo' => retriveMedia(). $this->photo,
            'description' => $this->description ?? '',
            'status' => $this->status,
            'spaces' => $this->spaces,
            'spaces_unit' => $this->spaces_unit,


        ];
    }
}
