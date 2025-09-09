<?php

namespace App\Domains\Tickets\Http\Requests;

use App\Domains\Projects\Enums\ProjectCategoryEnum;
use App\Domains\Projects\Enums\ProjectCategoryStatusEnum;
use App\Domains\Visits\Enums\VisitNextActionSourceEnum;
use App\Domains\Visits\Enums\VisitProductDiscussedSourceEnum;
use App\Domains\Visits\Enums\VisitTypeSourceEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string'],
            'deadline' => ['nullable', 'date'],
            'company_id' => ['nullable', 'integer'],
            'billable' => ['sometimes', 'in:true,false'],
            'public' => ['sometimes', 'in:true,false'],
        ];

        return $rules;

    }

    public function messages(): array
    {
        return [
            'name.required' => 'Το όνομα είναι απαραίτητο.',
            'deadline.required' => 'Η ημερομηνία είναι απαραίτητη.',
            'company_id.required' => 'Ο πελάτης είναι απαραίτητος.',
            'assignees.required' => 'Η ανάθεση χρηστών είναι απαραίτητη.',
        ];
    }
}
