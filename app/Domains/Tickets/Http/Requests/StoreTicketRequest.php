<?php

namespace App\Domains\Tickets\Http\Requests;

use App\Domains\Visits\Enums\VisitNextActionSourceEnum;
use App\Domains\Visits\Enums\VisitProductDiscussedSourceEnum;
use App\Domains\Visits\Enums\VisitTypeSourceEnum;
use App\Helpers\Enums\ActionTypesEnum;
use App\Helpers\Enums\PriorityEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->routeIs('admin.visits.store')) {
            $this->merge([
                'action_type' => ActionTypesEnum::VISITS->value,
            ]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string'],
            'deadline' => ['nullable', 'date'],
            'company_id' => ['nullable', 'integer'],
            'priority' => ['sometimes', new Enum(PriorityEnum::class)],
            'billable' => ['sometimes', 'in:true,false'],
            'public' => ['sometimes', 'in:true,false'],
            'assignees' => ['sometimes', 'array'],
            'assignees.*' => ['integer'],
            'files' => ['nullable','array'],
            'files.*' => ['file'],
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
