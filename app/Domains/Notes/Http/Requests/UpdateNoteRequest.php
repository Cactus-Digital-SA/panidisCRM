<?php

namespace App\Domains\Notes\Http\Requests;


class UpdateNoteRequest extends NoteRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
