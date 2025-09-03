<?php

namespace App\Domains\Tickets\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKanbanPositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
//        return auth()->user()->can('reports.edit');
    }

    public function rules(): array
    {
        $rules = [
            'source_board_id' => ['required', 'string'],
            'target_board_id' => ['required', 'string'],
            'new_position' => ['required', 'int'],
        ];

        return $rules;

    }

    public function messages(): array
    {
        return [

        ];
    }
}
