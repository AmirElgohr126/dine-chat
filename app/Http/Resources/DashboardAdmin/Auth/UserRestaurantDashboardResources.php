<?php

namespace App\Http\Resources\DashboardAdmin\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRestaurantDashboardResources extends JsonResource
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
            "restaurant_id" => $this->restaurant_id,
            "name" => $this->name,
            "user_name" => $this->user_name,
            "email" => $this->email,
            "photo" => retriveMedia() . $this->photo,
            "phone" => $this->phone,
            "status" => $this->status,
            "expire_subscription" => $this->expire_subscription,
        ];
    }
}
