<?php

namespace App\Http\Requests\V1\App\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $baseRules = [
            'type' => 'required|string|in:restaurant,public_place',
        ];

        // Based on the 'type', merge the specific rules
        if ($this->input('type') === 'restaurant') {
            return array_merge($baseRules, $this->restaurantRules());
        } else {
            return array_merge($baseRules, $this->publicPlaceRules());
        }
    }



    public function restaurantRules(): array
    {
        return [
            'restaurant_id' => 'required',
            'latitude' => 'required|numeric', // 'latitude' => 'required|numeric|between:-90,90'
            'longitude' => 'required|numeric', // 'longitude' => 'required|numeric|between:-180,180'
            'nfc_number' => 'required|numeric',

        ];
    }

    public function publicPlaceRules(): array
    {
        return [
            'public_place_id' => 'required|numeric|exists:public_places,id',
            'latitude' => 'required|numeric', // 'latitude' => 'required|numeric|between:-90,90'
            'longitude' => 'required|numeric', // 'longitude' => 'required|numeric|between:-180,180'

        ];
    }

}
