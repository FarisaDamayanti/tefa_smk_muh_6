<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'image' => ['nullable', 'sometimes', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'gender' => ['required', Rule::in(array_column(GenderEnum::cases(), 'value'))]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string'   => 'Nama harus berupa teks.',
            'name.max'      => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.string'   => 'Email harus berupa teks.',
            'email.email'    => 'Format email tidak valid.',
            'email.max'      => 'Email maksimal 255 karakter.',

            'address.string' => 'Alamat harus berupa teks.',
            'address.max'    => 'Alamat maksimal 500 karakter.',

            'phone_number.string' => 'Nomor telepon harus berupa teks.',
            'phone_number.max'    => 'Nomor telepon maksimal 15 karakter.',

            'image.image'    => 'File harus berupa foto',
            'image.mimes'    => 'Foto harus berupa PNG, JPG atau JPEG',
            'image.max'      => 'Foto harus lebih kecil dari 2MB',

            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in'       => 'Jenis kelamin tidak valid.',
        ];
    }
}
