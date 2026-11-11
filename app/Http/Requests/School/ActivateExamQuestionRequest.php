<?php

namespace App\Http\Requests\School;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ActivateExamQuestionRequest extends ApiRequest
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
            'questions' => ['required', 'array'],
            'questions.*' => ['required', 'string', 'distinct', 'exists:exam_questions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'questions.required' => 'Pertanyaan harus diisi.',
            'questions.array' => 'Pertanyaan harus berupa array',
            'questions.*.required' => 'Id pertanyaan diperlukan',
            'questions.*.string' => 'Id pertanyaan harus berupa string',
            'questions.*.exists' => 'Id pertanyaan tidak valid',
            'questions.*.distinct' => 'Terdapat duplikasi id pertanyaan',
        ];
    }



}
