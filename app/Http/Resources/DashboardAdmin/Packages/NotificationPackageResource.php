<?php

namespace App\Http\Resources\DashboardAdmin\Packages;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Return the specific structure you want for your NotificationPackage resource
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => retriveMedia(). $this->photo ?? '',
            'description' => $this->description,
            'notification_limit' => $this->notification_limit,
            'price_per_month' => $this->price_per_month,
            'price_per_year' => $this->price_per_year,
            'status' => $this->status,
            'period_finished_deleted_after' => $this->period_finished_deleted_after,
            'period_finished_unit' => $this->period_finished_unit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
