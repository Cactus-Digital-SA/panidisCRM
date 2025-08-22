<?php

namespace App\Domains\ExtraData\Http\Requests;

use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
use Illuminate\Foundation\Http\FormRequest;

class DeleteExtraDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function messages(): array
    {
        return [];
    }
}
