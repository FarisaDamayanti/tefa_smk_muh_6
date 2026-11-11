<?php

namespace App\Http\Requests\School;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionBankRequest extends ApiRequest
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
        $isUpdate = $this->method() == 'PUT';

        return [
            'title' => $this->updateRule($isUpdate, ['string', 'max:255']),
            'description' => $this->updateRule($isUpdate, ['string', 'max:65535 ']),
            'question_limit' => $this->updateRule($isUpdate, ['numeric', 'min:1']),
            'subject_id' => $this->updateRule($isUpdate, ['exists:subjects,id']),
            'level_class_id' => $this->updateRule($isUpdate, ['exists:level_classes,id']),
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul Bank Soal wajib diisi.',
            'title.string' => 'Judul Bank Soal harus berupa teks.',
            'title.max' => 'Judul Bank Soal maksimal :max karakter.',

            'description.required' => 'Deskripsi Bank Soal wajib diisi.',
            'description.string' => 'Deskripsi Bank Soal harus berupa teks.',
            'description.max' => 'Deskripsi Bank Soal maksimal :max karakter.',

            'question_limit.required' => 'Jumlah soal wajib diisi.',
            'question_limit.numeric' => 'Jumlah soal harus berupa angka.',
            'question_limit.min' => 'Jumlah soal minimal :min.',

            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists' => 'Mata pelajaran tidak valid.',

            'school_year_id.required' => 'Tahun pelajaran wajib dipilih.',
            'school_year_id.exists' => 'Tahun pelajaran tidak valid.',

            'level_class_id.required' => 'Tingkat kelas wajib dipilih.',
            'level_class_id.exists' => 'Tingkat kelas tidak valid.',
        ];
    }

    private function updateRule($isUpdate, $rules)
    {
        return [Rule::when($isUpdate, ['nullable', 'sometimes'], ['required']), ...$rules];
    }
}
