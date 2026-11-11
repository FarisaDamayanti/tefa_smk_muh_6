<?php

namespace App\Http\Requests\Teacher;

use App\Enums\AnswerOptionEnum;
use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExamQuestionChoiceRequest extends ApiRequest
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
            'question' =>  ['required', 'string', 'max:4294967295'],
            'options' => ['required', 'array', 'min:5', 'max:5'],
            'options.*.option_label' =>  ['required', Rule::in(AnswerOptionEnum::arrayCases())],
            'options.*.option_text' => ['required', 'string', 'max:4294967295'],
            'options.*.is_correct' => ['required', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Tipe soal wajib diisi.',
            'type.in' => 'Tipe soal harus salah satu opsi yang tersedia.',

            'question.required' => 'Pertanyaan wajib diisi.',
            'question.string' => 'Pertanyaan harus berupa teks.',
            'question.max' => 'Pertanyaan melebihi batas maksimum karakter.',

            'options.required' => 'Pilihan wajib diisi.',
            'options.array' => 'Pilihan tidak valid.',
            'options.min' => 'Pilihan harus terdapat 5.',

            'options.*.option_label.required' => 'Label pertanyaan tidak valid.',
            'options.*.option_label.in' => 'Label pertanyaan tidak valid.',

            'options.*.option_text.required' => 'Opsi A wajib diisi.',
            'options.*.option_text.string' => 'Opsi A harus berupa teks.',
            'options.*.option_text.max' => 'Opsi A melebihi batas maksimum karakter.',

            'options.*.is_correct.required' => 'Anda harus menentukan satu atau lebih opsi jawaban yang benar dari pilihan A, B, C, D, atau E sebelum menyimpan soal.',
            'options.*.is_correct.boolean' => 'Jawaban benar tidak valid.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $options = $this->input('options', []);

            $correctCount = collect($options)
                ->filter(fn ($option) => filter_var($option['is_correct'], FILTER_VALIDATE_BOOLEAN))
                ->count();

            if ($correctCount < 1) {
                $validator->errors()->add(
                    'options',
                    'Anda harus menentukan satu atau lebih opsi jawaban yang benar dari pilihan A, B, C, D, atau E sebelum menyimpan soal.'
                );
            }
        });
    }
}
