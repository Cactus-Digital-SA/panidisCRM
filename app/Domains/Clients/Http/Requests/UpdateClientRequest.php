<?php

namespace App\Domains\Clients\Http\Requests;

use App\Domains\Clients\Repositories\Eloquent\Models\ClientStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clientCompanyId' => ['required', Rule::exists('companies', 'id')],
            'statusId' => ['required', new Enum(ClientStatusEnum::class)],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
