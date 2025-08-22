<?php

namespace App\Domains\ExtraData\Http\Requests;

use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
class StoreExtraDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['required', new Enum(ExtraDataTypesEnum::class)],
            'options' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
