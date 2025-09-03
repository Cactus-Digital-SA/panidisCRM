<?php

namespace App\Domains\Tickets\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
//        return auth()->user()->can('reports.edit');
    }

    public function rules(): array
    {
        $rules = [
            'ticket_status' => ['required', 'string'],
        ];

        return $rules;

    }

    public function messages(): array
    {
        return [

        ];
    }
}
