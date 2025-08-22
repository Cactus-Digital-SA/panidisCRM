<?php

namespace App\Domains\Auth\Http\Requests\User;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateUserRequest.
 */
class UpdateUserDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('admin.access.user.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstName' => ['required', 'max:100'],
            'lastName' => ['required', 'max:100'],
            'birthday' => ['nullable', 'date'],
            'phone' => ['required', 'string'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }


}
