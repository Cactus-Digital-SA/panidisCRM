<?php

namespace App\Domains\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendSMSNotificationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
