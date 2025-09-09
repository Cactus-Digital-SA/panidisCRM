<?php

namespace App\Domains\Visits\Http\Requests;

use App\Domains\Visits\Enums\VisitNextActionSourceEnum;
use App\Domains\Visits\Enums\VisitProductDiscussedSourceEnum;
use App\Domains\Visits\Enums\VisitTypeSourceEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string'],
            'visit_date' => ['nullable', 'date'],
            'visit_type' => ['nullable', new Enum(VisitTypeSourceEnum::class)],
            'outcome' => ['nullable', 'string'],
            'products_discussed' => ['nullable', new Enum(VisitProductDiscussedSourceEnum::class)],
            'contacts' => ['sometimes', 'array'],
            'contacts.*' => ['integer'],
            'next_action' => ['nullable', new Enum(VisitNextActionSourceEnum::class)],
            'note' => ['nullable', 'string'],
            'files' => ['nullable','array'],
            'files.*' => ['file'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Το όνομα είναι απαραίτητο.',
        ];
    }
}
