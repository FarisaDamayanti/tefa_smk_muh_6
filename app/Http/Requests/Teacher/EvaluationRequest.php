<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationRequest extends FormRequest
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
            'evaluation' => ['array', 'min:1'],
            'evaluation.*.id' => ['required', 'exists:task_submissions,id'],
            'evaluation.*.grade' => ['required', 'integer', 'min:1', 'max:100']
        ];
    }

    public function messages()
    {
        return [
            'evaluation.array' => 'Evaluasi perlu berbentuk array.',
            'evaluation.min'   => 'Evaluasi perlu minimal satu penilaian.',

            'evaluation.*.id.required' => 'Penilaian pada task submission di perlukan.',
            'evaluation.*.id.exists' => 'Pengumpulan tidak valid.',

            'evaluation.*.grade.required'    => 'Nilai diperlukan.',
            'evaluation.*.grade.integer'    => 'Nilai harus berupa angka.',
            'evaluation.*.grade.min'    => 'Nilai minimum 1.',
            'evaluation.*.grade.max'    => 'Nilai maksimum 100.',
        ];
    }
}
