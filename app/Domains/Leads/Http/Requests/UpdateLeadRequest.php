<?php

namespace App\Domains\Leads\Http\Requests;

use App\Domains\Leads\Repositories\Eloquent\Models\LeadStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leadCompanyId' => ['required', Rule::exists('companies', 'id')],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
