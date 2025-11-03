<?php

namespace App\Domains\Quotes\Http\Requests;

use App\Domains\Auth\Models\RolesEnum;
use Illuminate\Foundation\Http\FormRequest;

class ManageQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return \Auth::user()->hasRole(RolesEnum::Administrator->value) || $this->user()->can('quotes.view');
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
