<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreExamQuestionMatchingRequest extends ApiRequest
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
            'questions.*.*' => ['required', 'string', 'max:4294967295']
        ];
    }

    public function messages(): array
    {
        return [
            'score.required' => 'Bobot soal wajib diisi.',
            'score.int'      => 'Bobot soal harus berupa angka bulat.',
            'score.min'      => 'Nilai minimal Bobot soal adalah 1.',
            'score.max'      => 'Nilai maksimal Bobot soal adalah 100.',

            'question.required' => 'Pertanyaan utama wajib diisi.',
            'question.string'   => 'Pertanyaan utama harus berupa teks.',
            'question.max'      => 'Pertanyaan utama melebihi batas maksimal karakter.',

            'questions.required' => 'Daftar pasangan soal wajib diisi.',
            'questions.array'    => 'Format pasangan soal tidak valid.',
            // 'questions.size'     => 'Soal menjodohkan harus terdiri dari tepat 4 pasangan.',

            'questions.*.required' => 'Setiap pasangan wajib diisi lengkap.',
            'questions.*.array'    => 'Format pasangan tidak valid.',
            'questions.*.size'     => 'Setiap pasangan harus terdiri dari 2 item.',

            'questions.*.*.required' => 'Teks pada setiap item pasangan wajib diisi.',
            'questions.*.*.string'   => 'Teks item pasangan harus berupa teks.',
            'questions.*.*.max'      => 'Teks item pasangan melebihi batas maksimal karakter.',
        ];
    }
}
