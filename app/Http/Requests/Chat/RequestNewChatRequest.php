<?php

namespace App\Http\Requests\Chat;

use App\Models\UserAttendance;
use Illuminate\Foundation\Http\FormRequest;

class RequestNewChatRequest extends FormRequest
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
        $userId = auth()->id(); // Get the authenticated user ID

        return [
            'message' => ['required', 'string'],
            'user_id' => ['required', 'exists:users,id'],
            'restaurant_id' => ['required', 'exists:restaurants,id']
        ];
    }

}
