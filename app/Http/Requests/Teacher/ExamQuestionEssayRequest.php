<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ExamQuestionEssayRequest extends ApiRequest
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
            'question' => ['required', 'string', 'max:4294967295'],
            'score' => ['required', 'int', 'min:1', 'max:100']
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => 'Soal uraian wajib diisi.',
            'question.string'   => 'Soal uraian harus berupa teks yang valid.',
            'question.max'      => 'Soal uraian melebihi batas maksimal karakter yang diperbolehkan.',

            'score.required' => 'Bobot soal diperlukan.',
            'score.int' => 'Bobot harus berupa angka.',
            'score.min'      => 'Nilai minimal Bobot soal adalah :min.',
            'score.max'      => 'Nilai maksimal Bobot soal adalah :max.',
        ];
    }
}
