<?php

namespace App\Domains\Companies\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteAssignUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'deleteUserId' => 'required_if:type,user',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
