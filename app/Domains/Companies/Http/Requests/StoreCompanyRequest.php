<?php

namespace App\Domains\Companies\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'activity' => ['nullable', 'string'],
            'typeId' => ['required', 'exists:company_types,id'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
