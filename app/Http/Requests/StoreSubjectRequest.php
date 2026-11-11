<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
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
            'name' => 'required|unique:subjects,name,except,id',
            'category' => 'required|in:umum,keagamaan',
            'religion_id' => 'nullable|exists:religions,id',
        ];
    }

    /**
     * Pesan kesalahan yang berlaku untuk permintaan ini.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama mapel wajib diisi.',
            'category.required' => 'Kategori mapel wajib diisi.',
            'name.unique' => 'Mata pelajaran sudah ada',
            'religion_id.exists' => 'Agama tidak ditemukan.',
            'category.in' => 'Kategori harus berupa umum atau keagamaan.'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('showCreateSubject', true);
        throw new \Illuminate\Validation\ValidationException($validator, redirect()->back()->withErrors($validator, 'create'));
    }
}
