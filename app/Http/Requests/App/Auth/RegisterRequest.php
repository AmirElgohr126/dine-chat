<?php

namespace App\Http\Requests\App\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'user_name' => 'required|string|unique:users,user_name|max:255', // Assuming 'users' is the table name , user_name columns
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'required|numeric|unique:users,phone',
            'device_token' => 'nullable|string|max:255',
            'password' => [
                'required',
                'string',
                'min:8',                    // Minimum length
                'regex:/^(?=.*[A-Z])/',     // At least one uppercase letter
                'regex:/^(?=.*[a-z])/',     // At least one lowercase letter
                'regex:/^(?=.*[0-9])/',     // At least one digit
                'regex:/^(?=.*[@$!%*?&])/'], // At least one special character
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:6144', // Assuming 'image' is the field type in your database
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function processedData()
    {
        // Get the validated data in ******** array ********
        $validated = parent::validated();
        $validated['password'] = Hash::make($validated['password']); // hash password

        return $validated;
    }
}
