<?php

namespace App\Domains\ErpSales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManageErpSalesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }
}
