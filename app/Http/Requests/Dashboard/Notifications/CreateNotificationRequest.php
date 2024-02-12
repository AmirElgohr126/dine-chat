<?php
namespace App\Http\Requests\Dashboard\Notifications;

use Illuminate\Foundation\Http\FormRequest;

class CreateNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'message' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:6144',
            'status' => 'required|in:send_now,pending',
            'sent_at' => 'sometimes|nullable|required_if:status,pending|date|after:now',
        ];
    }
}

