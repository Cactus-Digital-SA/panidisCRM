<?php

namespace App\Domains\CompanyTypes\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCompanyTypeRequest extends FormRequest
{

    /**
     * @return true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string'],
        ];
    }

    /**
     * @param Validator $validator
     * @return void
     */
    public function failedValidation(Validator $validator): void
    {
        if ($this->wantsJson()) {
            // Request comes from API
            $errors = $validator->errors();

            $response = response()->json([
                'message' => 'Invalid data sent',
                'details' => $errors->messages(),
            ], 422);

            throw new HttpResponseException($response);
        } else {
            // Request comes from internal app
            parent::failedValidation($validator);
        }
    }

}
