<?php

namespace App\Domains\Auth\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ReactiveUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('admin.access.user.reactivate');
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
