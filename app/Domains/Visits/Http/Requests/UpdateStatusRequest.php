<?php

namespace App\Domains\Visits\Http\Requests;

use App\Domains\Auth\Models\RolesEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return \Auth::user()->hasRole(RolesEnum::Administrator->value) || \Auth::user()->can('visits.update');
    }

    public function rules(): array
    {
        $rules = [
            'visit_status' => ['required', 'string'],
        ];

        return $rules;

    }

    public function messages(): array
    {
        return [

        ];
    }
}
