<?php

namespace App\Domains\Auth\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ManageUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('admin.access.user.list');
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
