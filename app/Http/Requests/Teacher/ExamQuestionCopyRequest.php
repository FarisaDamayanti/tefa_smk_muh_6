<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ExamQuestionCopyRequest extends ApiRequest
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
            'questions' => ['required', 'array', 'min:1'],
            'questions.*' => ['required', 'exists:exam_questions,id']
        ];
    }

    public function messages()
    {
        return [
            'questions.required' => 'Kumpulan soal yang akan di salin diperlukan.',
            'questions.array' => 'Kumpulan soal harus berupa array.',
            'questions.min' => 'Kumpulan soal minimal :min.',
            'questions.*.required' => 'Soal diperlukan.',
            'questions.*.exists' => 'Soal tidak valid.',
        ];
    }
}
