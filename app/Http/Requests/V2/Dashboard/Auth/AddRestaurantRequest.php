<?php

namespace App\Http\Requests\V2\Dashboard\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AddRestaurantRequest extends FormRequest
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
        return [
            'first_name' => ['required','string'],
            'last_name' => ['required','string'],
            'email' => ['required', 'email', 'unique:applcation_for_restaurants,email'],
            'phone' => ['required','numeric'],
            'order' => ['required','string'],
            'restaurant_name' => ['required','string'],
        ];
    }
}
