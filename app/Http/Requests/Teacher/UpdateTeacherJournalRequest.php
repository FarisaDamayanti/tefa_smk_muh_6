<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherJournalRequest extends FormRequest
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
            'title' => 'required|max:60000',
            'description' => 'required',
            'date' => 'required|date'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Topik wajib diisi.',
            'title.max' => 'Topik tidak boleh lebih dari :max karakter.',
            'description' => 'Deskripsi wajib diisi.',
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Tanggal tidak valid.',
        ];
    }
}
