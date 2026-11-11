<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegulationRequest extends FormRequest
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
            'violation' => 'required|max:255',
            'point' => 'required|numeric|min:1|max:5000'
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
            'violation.required' => 'Kolom pelanggaran harus diisi.',
            'violation.max' => 'Kolom pelanggaran tidak boleh lebih dari :max karakter.',
            'point.required' => 'Kolom poin harus diisi.',
            'point.numeric' => 'Poin harusnya numerik.',
            'point.min' => 'Poin minimal :min.',
            'point.max' => 'Poin maksimal :max.',
        ];
    }
}
