<?php

namespace App\Http\Requests\Student;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class StartExamRequest extends ApiRequest
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
            'token' => 'required|string|max:6',
        ];
    }

    public function messages()
    {
        return [
            'token.required' => 'Token wajib diisi.',
            'token.string' => 'Token tidak valid.',
            'token.max' => 'Token maksimal :max karakter.',
        ];
    }
}
