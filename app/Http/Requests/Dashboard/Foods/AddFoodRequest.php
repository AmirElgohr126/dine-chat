<?php

namespace App\Http\Requests\Dashboard\Foods;

use Illuminate\Foundation\Http\FormRequest;

class AddFoodRequest extends FormRequest
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
            'ar.name' => ['required','string'],
            'en.name' => ['required','string'],
            'price' => ['required','numeric'],
            'status' => ['required','in:active,inactive','string'],
            'details' => ['required','string'],
            'photo' => ['required','image','mimes:jpeg,png,jpg,gif,svg','max:6144']
        ];
    }
}
