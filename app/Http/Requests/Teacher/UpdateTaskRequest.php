<?php

namespace App\Http\Requests\Teacher;

use App\Enums\SubmissionTypeEnum;
use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends ApiRequest
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
            'title' => 'required|max:255',
            'description' => 'required|max:60000',
            'deadline' => 'required|date',
            'submission_type' => 'required|in:' . SubmissionTypeEnum::FILE->value . ',' . SubmissionTypeEnum::LINK->value,
            'allow_overdue' => 'required|boolean',
        ];
    }
    
    public function messages(): array
    {
        return [
            'title.required' => 'Judul tugas wajib diisi.',
            'title.max' => 'Judul tugas maksimal :max karakter.',
            'description.required' => 'Deskripsi tugas wajib diisi.',
            'description.max' => 'Deskripsi tugas maksimal :max karakter.',
            'deadline.required' => 'Tenggat wajib diisi.',
            'deadline.date' => 'Tenggat tidak valid.',
            'submission_type.required' => 'Jenis pengumpulan wajib diisi.',
            'submission_type.in' => 'Jenis pengumpulan tidak valid.',
            'allow_overdue.required' => 'Izin pengumpulan terlambat wajib diisi.',
            'allow_overdue.boolean' => 'Izin pengumpulan terlambat tidak valid.',
        ];
    }
}
