<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ExamQuestionOptionRequest extends ApiRequest
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
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|exists:exam_question_options,id'
        ];
    }

    public function messages()
    {
        return [
            'ids.required' => 'Pilihan jawaban wajib diisi.',
            'ids.array' => 'Pilihan jawaban harus berupa array.',
            'ids.min' => 'Pilihan jawaban minimal 1.',
            'ids.*.required' => 'Pilihan jawaban wajib diisi.',
            'ids.*.exists' => 'Pilihan jawaban tidak valid.',
        ];
    }
}
