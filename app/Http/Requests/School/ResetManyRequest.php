<?php

namespace App\Http\Requests\School;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ResetManyRequest extends ApiRequest
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
            'student_exam_ids' => 'required|array|min:1',
            'student_exam_ids.*' => 'required|exists:student_exams,id',
        ];
    }

    public function messages()
    {
        return [
            'student_exam_ids.required' => 'Ujian siswa wajib diisi.',
            'student_exam_ids.array' => 'Ujian siswa tidak valid.',
            'student_exam_ids.min' => 'Ujian siswa minimal 1.',
            'student_exam_ids.*.required' => 'Id ujian siswa wajib diisi.',
            'student_exam_ids.*.exists' => 'Id ujian siswa tidak valid.',
        ];
    }
}
