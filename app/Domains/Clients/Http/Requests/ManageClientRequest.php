<?php

namespace App\Domains\Clients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManageClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }
}
