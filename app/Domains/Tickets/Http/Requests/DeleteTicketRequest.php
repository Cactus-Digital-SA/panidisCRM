<?php

namespace App\Domains\Tickets\Http\Requests;

use App\Domains\Auth\Models\RolesEnum;
use Illuminate\Foundation\Http\FormRequest;

class DeleteTicketRequest extends FormRequest
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
