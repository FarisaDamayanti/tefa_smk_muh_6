<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskSubmissionNoteRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'note' => 'required|max:60000'
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' => 'Catatan wajib diisi.',
            'note.max' => 'Catatan maksimal :max karakter.',
        ];
    }
}
