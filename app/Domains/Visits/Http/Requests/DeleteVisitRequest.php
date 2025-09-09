<?php

namespace App\Domains\Visits\Http\Requests;

use App\Domains\Auth\Models\RolesEnum;
use Illuminate\Foundation\Http\FormRequest;

class DeleteVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return \Auth::user()->hasRole(RolesEnum::Administrator->value);
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function messages(): array
    {
        return [];
    }
}
