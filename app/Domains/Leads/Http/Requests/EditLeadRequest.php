<?php

namespace App\Domains\Leads\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditLeadRequest extends FormRequest
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
