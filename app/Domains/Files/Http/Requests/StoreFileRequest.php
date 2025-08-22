<?php

namespace App\Domains\Files\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('file.create');
    }

    public function rules()
    {
        return [
            'files' => ['nullable','array'],
            'files.*' => ['file'],
            'file' => ['nullable','array'],
            'file.*' => ['file'],
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
