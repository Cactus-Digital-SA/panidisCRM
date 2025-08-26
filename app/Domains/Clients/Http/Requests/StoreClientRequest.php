<?php

namespace App\Domains\Clients\Http\Requests;

use App\Domains\Clients\Repositories\Eloquent\Models\ClientStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'statusId' => ['required', new Enum(ClientStatusEnum::class)],
            'existing_company_id' => ['nullable', 'exists:companies,id'],
            'newCompanyName' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}
