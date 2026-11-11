<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
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
            'store-class' => 'required|array',
            'store-class.*.name' => 'required',
            'store-class.*.employee_id' => 'required',
            'store-class.*.level_class_id' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'store-class.*.name.required' => 'Nama kelas wajib diisi.',
            'store-class.*.employee_id.required' => 'Wali kelas wajib dipilih.',
            'store-class.*.level_class_id.required' => 'Tingkatan kelas wajib dipilih.',
        ];
    }
}
