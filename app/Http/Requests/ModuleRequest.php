<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'meeting_number' => ['required', 'integer', 'min:1', 'max:32'],
            'content' => ['nullable', 'sometimes', 'string', 'max:65535'],
            'link' => ['nullable', 'sometimes', 'url', 'string', 'max:255'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
        ];
    }

    public function messages()
    {
        return  [
            'title.required' => 'Judul wajib diisi.',
            'title.string'   => 'Judul harus berupa teks.',
            'title.max'      => 'Judul maksimal 255 karakter.',

            'meeting_number.required' => 'Nomor pertemuan wajib diisi.',
            'meeting_number.numeric'  => 'Nomor pertemuan harus berupa angka.',
            'meeting_number.min' => 'Nomor pertemuan minimal 1.',

            'meeting_number.max'      => 'Nomor pertemuan maksimal 32.',

            'content.required' => 'Konten wajib diisi.',
            'content.string'   => 'Konten harus berupa teks.',
            'content.max'      => 'Konten maksimal 65.535 karakter.',

            'link.string' => 'Link tidak valid.',
            'link.url' => 'Link url tidak valid.',
            'link.max'      => 'Link maksimal 255 karakter.',

            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists'   => 'Mata pelajaran tidak valid.',

            'classroom_id.required' => 'Kelas wajib dipilih.',
            'classroom_id.exists'   => 'Kelas tidak valid.',
        ];
    }
}
