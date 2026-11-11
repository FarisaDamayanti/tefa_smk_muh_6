<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskSubmissionGradeRequest extends ApiRequest
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
            'scores' => 'required|array|min:1',
            'scores.*.id' => 'required|exists:task_submissions,id',
            'scores.*.grade' => 'nullable|integer|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            'scores.required' => 'Data nilai wajib diisi.',
            'scores.array' => 'Data nilai harus berupa array.',
            'scores.min' => 'Minimal harus ada 1 data nilai.',
            'scores.*.id.required' => 'ID submission wajib diisi.',
            'scores.*.id.exists' => 'ID submission tidak valid.',
            'scores.*.grade.required' => 'Nilai wajib diisi.',
            'scores.*.grade.integer' => 'Nilai harus berupa angka.',
            'scores.*.grade.min' => 'Nilai minimal :min.',
            'scores.*.grade.max' => 'Nilai maksimal :max.',
        ];
    }
}
