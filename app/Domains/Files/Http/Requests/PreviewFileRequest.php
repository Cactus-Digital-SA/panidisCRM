<?php

namespace App\Domains\Files\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreviewFileRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('file.preview');
    }

    public function rules()
    {
        return [
            'filePath' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
