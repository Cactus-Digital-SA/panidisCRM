<?php

namespace App\Domains\ExtraData\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtraDataModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'extraData.*' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
