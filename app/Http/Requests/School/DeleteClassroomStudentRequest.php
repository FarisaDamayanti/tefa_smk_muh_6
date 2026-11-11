<?php

namespace App\Http\Requests\School;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class DeleteClassroomStudentRequest extends ApiRequest
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
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:classroom_students,id'
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
            'ids.required' => 'Siswa wajib diisi.',
            'ids.array' => 'Siswa wajib bertipe array.',
            'ids.min' => 'Minimal terdapat 1 siswa.',
            'ids.*.exists' => 'Id Siswa tidak sesuai.',
        ];
    }
}
