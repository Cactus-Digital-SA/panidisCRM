<?php

namespace App\Domains\Tickets\Http\Requests;

use App\Domains\Tickets\Enums\TicketActionTypesEnum;
use App\Domains\Tickets\Enums\VisitNextActionSourceEnum;
use App\Domains\Tickets\Enums\VisitProductDiscussedSourceEnum;
use App\Domains\Tickets\Enums\VisitTypeSourceEnum;
use App\Helpers\Enums\PriorityEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
                'action_type' => TicketActionTypesEnum::VISITS->value,
            ]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'files' => ['nullable','array'],
            'files.*' => ['file'],
        ];

        if ($this->routeIs('admin.tickets.store')) {
            $rules['name'] = ['required', 'string'];
            $rules['deadline'] = ['nullable', 'date'];
            $rules['company_id'] = ['nullable', 'integer'];
            $rules['priority'] = ['sometimes', new Enum(PriorityEnum::class)];
            $rules['billable'] = ['sometimes', 'in:true,false'];
            $rules['public'] = ['sometimes', 'in:true,false'];
            $rules['assignees'] = ['sometimes', 'array'];
            $rules['assignees.*'] = ['integer'];
        }

        if ($this->routeIs('admin.visits.store')) {
            $rules['visit_date'] = ['nullable', 'date'];
            $rules['visit_type'] = ['nullable', new Enum(VisitTypeSourceEnum::class)];
            $rules['outcome'] = ['nullable', 'string'];
            $rules['products_discussed'] = ['nullable', new Enum(VisitProductDiscussedSourceEnum::class)];
            $rules['contacts'] = ['sometimes', 'array'];
            $rules['contacts.*'] = ['integer'];
            $rules['next_action'] = ['nullable', new Enum(VisitNextActionSourceEnum::class)];
            $rules['note'] = ['nullable', 'string'];
        }


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
