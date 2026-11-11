<?php

namespace App\Http\Requests\Teacher;

use App\Enums\AnswerOptionEnum;
use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExamQuestionRequest extends ApiRequest
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
        return $this->handleBulkRequest($this->isBulk(), $this->isUpdate());
    }

    public function isUpdate()
    {
        return $this->method() == 'PUT';
    }

    public function isBulk()
    {
        return $this->filled('questions') && !empty($this->input('questions')) && is_null($this->route('examQuestion'));
    }

    public function messages(): array
    {
        return [
            'question.required' => 'Pertanyaan wajib diisi.',
            'question.string' => 'Pertanyaan harus berupa teks.',
            'question.max' => 'Pertanyaan melebihi batas maksimum karakter.',

            'option_a.required' => 'Opsi A wajib diisi.',
            'option_a.string' => 'Opsi A harus berupa teks.',
            'option_a.max' => 'Opsi A melebihi batas maksimum karakter.',

            'option_b.required' => 'Opsi B wajib diisi.',
            'option_b.string' => 'Opsi B harus berupa teks.',
            'option_b.max' => 'Opsi B melebihi batas maksimum karakter.',

            'option_c.required' => 'Opsi C wajib diisi.',
            'option_c.string' => 'Opsi C harus berupa teks.',
            'option_c.max' => 'Opsi C melebihi batas maksimum karakter.',

            'option_d.required' => 'Opsi D wajib diisi.',
            'option_d.string' => 'Opsi D harus berupa teks.',
            'option_d.max' => 'Opsi D melebihi batas maksimum karakter.',

            'option_e.required' => 'Opsi E wajib diisi.',
            'option_e.string' => 'Opsi E harus berupa teks.',
            'option_e.max' => 'Opsi E melebihi batas maksimum karakter.',

            'answer.required' => 'Anda harus menentukan satu opsi jawaban yang benar dari pilihan A, B, C, D, atau E sebelum menyimpan soal.',
            'answer.string' => 'Jawaban tidak valid.',
            'answer.in' => 'Jawaban harus salah satu dari opsi yang tersedia.',

            // bulk
            'questions.array' => 'Format data pertanyaan tidak valid.',
            'questions.min' => 'Minimal harus ada satu pertanyaan.',

            'questions.*.id.required' => 'Id pertanyaan diperlukan.',
            'questions.*.id.exists' => 'Id pertanyaan tidak valid.',
            'questions.*.question.required' => 'Pertanyaan wajib diisi.',
            'questions.*.question.string' => 'Pertanyaan harus berupa teks.',
            'questions.*.question.max' => 'Pertanyaan melebihi batas maksimum karakter.',

            'questions.*.option_a.required' => 'Opsi A wajib diisi.',
            'questions.*.option_a.string' => 'Opsi A harus berupa teks.',
            'questions.*.option_a.max' => 'Opsi A melebihi batas maksimum karakter.',

            'questions.*.option_b.required' => 'Opsi B wajib diisi.',
            'questions.*.option_b.string' => 'Opsi B harus berupa teks.',
            'questions.*.option_b.max' => 'Opsi B melebihi batas maksimum karakter.',

            'questions.*.option_c.required' => 'Opsi C wajib diisi.',
            'questions.*.option_c.string' => 'Opsi C harus berupa teks.',
            'questions.*.option_c.max' => 'Opsi C melebihi batas maksimum karakter.',

            'questions.*.option_d.required' => 'Opsi D wajib diisi.',
            'questions.*.option_d.string' => 'Opsi D harus berupa teks.',
            'questions.*.option_d.max' => 'Opsi D melebihi batas maksimum karakter.',

            'questions.*.option_e.required' => 'Opsi E wajib diisi.',
            'questions.*.option_e.string' => 'Opsi E harus berupa teks.',
            'questions.*.option_e.max' => 'Opsi E melebihi batas maksimum karakter.',

            'questions.*.answer.required' => 'Anda harus menentukan satu opsi jawaban yang benar dari pilihan A, B, C, D, atau E sebelum menyimpan soal.',
            'questions.*.answer.string' => 'Jawaban tidak valid.',
            'questions.*.answer.in' => 'Jawaban harus salah satu dari opsi yang tersedia.',
        ];
    }

    private function handleBulkRequest($isBulk, $isUpdate): array
    {
        if ($isBulk) {
            $base = [
                'questions' => ['array', 'min:1'],
                'questions.*.question' =>  ['required', 'string', 'max:4294967295'],
                'questions.*.option_a' =>  ['required', 'string', 'max:4294967295'],
                'questions.*.option_b' =>  ['required', 'string', 'max:4294967295'],
                'questions.*.option_c' =>  ['required', 'string', 'max:4294967295'],
                'questions.*.option_d' =>  ['required', 'string', 'max:4294967295'],
                'questions.*.option_e' =>  ['required', 'string', 'max:4294967295'],
                'questions.*.answer' =>  ['required', 'string', Rule::in(AnswerOptionEnum::arrayCases())],
            ];

            if($isUpdate){
                $base['questions.*.id'] = ['required', 'exists:exam_questions,id'];
            }

            return $base;
        }

        return [
            'question' =>  ['required', 'string', 'max:4294967295'],
            'option_a' =>  ['required', 'string', 'max:4294967295'],
            'option_b' =>  ['required', 'string', 'max:4294967295'],
            'option_c' =>  ['required', 'string', 'max:4294967295'],
            'option_d' =>  ['required', 'string', 'max:4294967295'],
            'option_e' =>  ['required', 'string', 'max:4294967295'],
            'answer' =>  ['required', 'string', Rule::in(AnswerOptionEnum::arrayCases())],
        ];
    }
}
