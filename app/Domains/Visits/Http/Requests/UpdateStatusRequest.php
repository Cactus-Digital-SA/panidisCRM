<?php

namespace App\Domains\Visits\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'visit_status' => ['required', 'string'],
        ];

        return $rules;

    }

    public function messages(): array
    {
        return [

        ];
    }
}
