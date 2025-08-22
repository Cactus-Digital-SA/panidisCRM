<?php

namespace App\Domains\Auth\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class EditRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('crud roles');
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}
