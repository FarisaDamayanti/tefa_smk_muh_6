<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolPointRequest extends FormRequest
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
            'repeater-group' => 'required',
            'repeater-group.*.point' => 'required|numeric|min:1|max:5000',
            'repeater-group.*.description' => 'required|string|max:10000',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'repeater-group.required' => 'Kolom pelanggaran harus diisi.',
            'repeater-group.*.point.required' => 'Kolom poin harus diisi.',
            'repeater-group.*.point.numeric' => 'Poin harusnya numerik.',
            'repeater-group.*.point.min' => 'Poin minimal :min.',
            'repeater-group.*.point.max' => 'Poin maksimal :max.',
            'repeater-group.*.description.required' => 'Kolom deskripsi harus diisi.',
            'repeater-group.*.description.max' => 'Kolom deskripsi tidak boleh lebih dari :max karakter.',
        ];
    }
}
