<?php

namespace App\Domains\Quotes\Http\Requests;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Visits\Enums\VisitNextActionSourceEnum;
use App\Domains\Visits\Enums\VisitProductDiscussedSourceEnum;
use App\Domains\Visits\Enums\VisitTypeSourceEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return \Auth::user()->hasRole(RolesEnum::Administrator->value) || \Auth::user()->can('quotes.update');
    }

    public function rules(): array
    {
        $rules = [];

        return $rules;

    }

    public function messages(): array
    {
        return [
            'name.required' => 'Το όνομα είναι απαραίτητο.',
        ];
    }
}
