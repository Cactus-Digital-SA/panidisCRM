<?php

namespace App\Domains\Widgets\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWidgetsRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'widgets.*' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
