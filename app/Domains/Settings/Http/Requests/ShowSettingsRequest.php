<?php

namespace App\Domains\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('settings.view');
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }
}
