<?php

namespace App\Domains\Projects\Http\Requests;

use App\Domains\Projects\Enums\ProjectCategoryEnum;
use App\Domains\Projects\Enums\ProjectCategoryStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date_format:d/m/Y'],
            'deadline' => ['nullable', 'date_format:d/m/Y'],
            'sales_cost' => ['nullable', 'numeric'],
            'google_drive' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
            'est_date' => ['nullable', 'date_format:d/m/Y'],
            'est_time' => ['nullable', 'integer'],

            'owner_id' => ['nullable', 'exists:users,id'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
//            'client_id' => [
//                Rule::requiredIf(fn () => $this->input('category') !== ProjectCategoryEnum::INTERNAL->value),
//            ],
            'assignees' => ['array', 'nullable'],
            'assignees.*' => ['exists:users,id'],
            'category' => ['sometimes', new Enum(ProjectCategoryEnum::class)],
            'category_status' => ['sometimes', new Enum(ProjectCategoryStatusEnum::class)],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
