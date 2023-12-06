<?php

namespace App\Http\Requests\Contacts;

use App\Models\UserFollower;
use Illuminate\Foundation\Http\FormRequest;

class FollowContactRequest extends FormRequest
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
            "contact_id" => "required|exists:contacts,id"
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if the user is already following the contact
            $isFollowing = UserFollower::where('user_id', $this->user()->id)->where('contact_id', $this->contact_id)->exists();
            if ($isFollowing) {
                $validator->errors()->add('contact_id', 'User is already following the contact.');
            }
        });
    }


}
