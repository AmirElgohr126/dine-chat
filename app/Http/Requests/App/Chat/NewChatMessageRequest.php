<?php

namespace App\Http\Requests\App\Chat;

use Illuminate\Foundation\Http\FormRequest;

class NewChatMessageRequest extends FormRequest
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
            'message' => 'nullable|string|min:0|max:500',
            'replay_on' => 'nullable|exists:message,id',
            'attachment' => 'nullable|file|max:6048|mimes:jpeg,png,gif,mp4,pdf',
        ];
    }
}
