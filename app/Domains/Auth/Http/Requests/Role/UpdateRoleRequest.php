<?php

namespace App\Domains\Auth\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('crud roles');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'permissions' => ['sometimes', 'array'],
        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}
