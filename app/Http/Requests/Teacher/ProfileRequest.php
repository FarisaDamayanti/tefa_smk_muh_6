<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends ApiRequest
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
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nip' => 'required|string|digits:18',
            'phone_number' => 'required|string|digits_between:12,13',
            'address' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'Gambar tidak valid.',
            'image.mimes' => 'Gambar harus berupa PNG, JPG, atau JPEG.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama tidak valid.',
            'name.max' => 'Nama maksimal :max karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'email.max' => 'email maksimal :max karakter.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.string' => 'NIP tidak valid',
            'nip.digits' => 'NIP wajib terdapat 18 digit',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.string' => 'Nomor telepon tidak valid.',
            'phone_number.digits_between' => 'Nomor telepon wajib terdapat 12-13 digit.',
            'address.required' => 'Alamat wajib diisi.',
            'address.string' => 'Alamat tidak valid.',
        ];
    }
}
