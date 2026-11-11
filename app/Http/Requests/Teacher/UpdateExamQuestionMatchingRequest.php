<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExamQuestionMatchingRequest extends ApiRequest
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
            'score' => ['required', 'int', 'min:1', 'max:100'],
            'question' => ['required', 'string', 'max:4294967295'],
            'questions' => ['required', 'array'],
            'questions.*' => ['required', 'array', 'size:2'],
            'questions.*.*.id' => ['sometimes', 'nullable', 'exists:exam_question_matchings,id'],
            'questions.*.*.item_text' => ['required', 'string', 'max:4294967295'],
            'delete' => ['nullable', 'sometimes', 'array'],
            'delete.*' => ['required', 'string', 'exists:exam_question_matchings,id']
        ];
    }

    public function messages()
    {
        return [
            'score.required' => 'Bobot soal wajib diisi.',
            'score.int'      => 'Bobot soal harus berupa angka bulat.',
            'score.min'      => 'Bobot soal minimal bernilai 1.',
            'score.max'      => 'Bobot soal maksimal bernilai 100.',

            'question.required' => 'Pertanyaan utama wajib diisi.',
            'question.string'   => 'Pertanyaan utama harus berupa teks.',
            'question.max'      => 'Pertanyaan utama melebihi batas maksimal karakter.',

            'questions.required' => 'Kumpulan pasangan wajid diisi.',
            'questions.array' => 'Kumpulan pasangan harus berupa array.',
            'questions.*.id.required' => 'Id pasangan wajid diisi.',
            'questions.*.id.exists' => 'Id pasangan tidak valid.',
            'questions.*.item_text.required' => 'Teks pada setiap item pasangan wajib diisi.',
            'questions.*.item_text.string'   => 'Teks item pasangan harus berupa teks.',
            'questions.*.item_text.max'      => 'Teks item pasangan melebihi batas maksimal karakter.',

            'delete.array' => 'Kumpulan id harus berupa array.',
            'delete.*.required' => 'Harus berupa string.',
            'delete.*.string' => 'Harus berupa string.',
            'delete.*.exists' => 'Id tidak valid.',
        ];
    }
}
