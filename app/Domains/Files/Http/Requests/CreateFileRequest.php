<?php

namespace App\Domains\Files\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFileRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('file.create');
    }

    public function rules()
    {
        return [
            'fileableId' => ['required', 'integer'],
            'fileableType' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
