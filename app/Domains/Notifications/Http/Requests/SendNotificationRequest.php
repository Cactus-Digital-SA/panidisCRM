<?php

namespace App\Domains\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'subject' => ['required', 'string'],
            'body' => ['required', 'string'],
            'recipients' => ['required', 'array'],
            'recipients.*.name' => ['required', 'string'],
            'recipients.*.email' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
