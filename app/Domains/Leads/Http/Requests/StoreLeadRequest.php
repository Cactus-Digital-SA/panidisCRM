<?php

namespace App\Domains\Leads\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'companyName' => ['required', 'string'],
            'erpId' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'activity' => ['nullable', 'string'],
            'typeId' => ['required', 'exists:company_types,id'],
            'sourceId' => ['required', 'exists:company_source,id'],
            'countryId' => ['required', 'exists:country_codes,id'],
            'city' => ['nullable', 'string'],
            'website' => ['nullable', 'string'],
            'linkedin' => ['nullable', 'string'],
            'tagIds' => ['nullable', 'array'],
            'extraDataIds' => ['nullable', 'array'],
            'extraDataIds.*' => ['exists:extra_data,id'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
