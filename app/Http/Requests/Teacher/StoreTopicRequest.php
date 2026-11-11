<?php

namespace App\Http\Requests\Teacher;

use App\Enums\SemesterEnum;
use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreTopicRequest extends ApiRequest
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
            'title.required' => 'Judul topik wajib diisi.',
            'title.string' => 'Judul topik tidak valid.',
            'title.max' => 'Judul topik maksimal :max karakter.',
            'description.required' => 'Deskripsi topik wajib diisi.',
            'description.string' => 'Deskripsi topik tidak valid.',
            'description.max' => 'Deskripsi topik maksimal :max karakter.',
            'semester.required' => 'Semester wajib diisi.',
            'semester.in' => 'Semester tidak valid.',
        ];
    }
}
