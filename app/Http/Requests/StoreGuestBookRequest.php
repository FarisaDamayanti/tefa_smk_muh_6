<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestBookRequest extends FormRequest
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
            'name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'required|numeric|max_digits:15',
            'position' => 'required|max:255',
            'needs' => 'required|max:5000',
            'date' => 'required|date',
            'status' => 'nullable',
            'address' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal :max karakter.',
            'email.email' => 'Email tidak valid.',
            'email.max' => 'Email maksimal :max karakter.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.numeric' => 'Nomor telepon harus berupa angka.',
            'phone_number.max_digits' => 'Nomor telepon maksimal :max_digits digit.',
            'position.required' => 'Jabatan wajib diisi.',
            'position.max' => 'Jabatan maksimal :max karakter.',
            'needs.required' => 'Keperluan wajib diisi.',
            'needs.max' => 'Keperluan maksimal :max karakter.',
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Tanggal tidak valid.',
        ];
    }
}
