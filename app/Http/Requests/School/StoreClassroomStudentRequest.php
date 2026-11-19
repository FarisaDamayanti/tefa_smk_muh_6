<?php

namespace App\Http\Requests\School;

use App\Http\Requests\ApiRequest;

class StoreClassroomStudentRequest extends ApiRequest
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
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|distinct|exists:students,id',
        ];
    }

    /**
     * messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'student_ids.required' => 'Siswa wajib diisi.',
            'student_ids.array' => 'Siswa wajib bertipe array.',
            'student_ids.min' => 'Minimal terdapat 1 siswa.',
            'student_ids.*.exists' => 'Id Siswa tidak sesuai.',
        ];
    }
}
