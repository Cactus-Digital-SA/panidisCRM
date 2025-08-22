<?php

namespace App\Domains\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailNotificationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'FromEmail' => ['required', 'string'],
            'ToEmail' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
