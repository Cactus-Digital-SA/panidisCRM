<?php

namespace App\Domains\Notes\Http\Requests;


class DeleteNoteRequest extends NoteRequest
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
