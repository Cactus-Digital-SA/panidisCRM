<?php

namespace App\Domains\Tickets\Http\Requests;

use App\Domains\Projects\Enums\ProjectCategoryEnum;
use App\Domains\Projects\Enums\ProjectCategoryStatusEnum;
use App\Domains\Tickets\Enums\VisitNextActionSourceEnum;
use App\Domains\Tickets\Enums\VisitProductDiscussedSourceEnum;
use App\Domains\Tickets\Enums\VisitTypeSourceEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];
        if ($this->routeIs('admin.tickets.store')) {
            $rules['name'] = ['required', 'string'];
            $rules['deadline'] = ['nullable', 'date'];
            $rules['company_id'] = ['nullable', 'integer'];
            $rules['billable'] = ['sometimes', 'in:true,false'];
            $rules['public'] = ['sometimes', 'in:true,false'];
        }

        if ($this->routeIs('admin.visits.store')) {
            $rules['visit_date'] = ['nullable', 'date'];
            $rules['visit_type'] = ['nullable', new Enum(VisitTypeSourceEnum::class)];
            $rules['outcome'] = ['nullable', 'string'];
            $rules['products_discussed'] = ['nullable', new Enum(VisitProductDiscussedSourceEnum::class)];
            $rules['contacts'] = ['sometimes', 'array'];
            $rules['contacts.*'] = ['integer'];
            $rules['next_action'] = ['nullable', new Enum(VisitNextActionSourceEnum::class)];
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
