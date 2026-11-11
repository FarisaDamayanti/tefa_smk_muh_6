<?php

namespace App\Http\Requests\Teacher;

use App\Enums\SemesterEnum;
use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTopicRequest extends ApiRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:65000',
            'semester' => 'required|in:' . SemesterEnum::GANJIL->value . ',' . SemesterEnum::GENAP->value,
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul topic wajib diisi.',
            'title.string' => 'Judul topic tidak valid.',
            'title.max' => 'Judul topic maksimal :max karakter.',
            'description.required' => 'Deskripsi topic wajib diisi.',
            'description.string' => 'Deskripsi topic tidak valid.',
            'description.max' => 'Deskripsi topic maksimal :max karakter.',
            'semester.required' => 'Semester wajib diisi.',
            'semester.in' => 'Semester tidak valid.',
        ];
    }
}
