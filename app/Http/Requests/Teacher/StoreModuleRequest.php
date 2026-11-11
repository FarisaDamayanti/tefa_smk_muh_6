<?php

namespace App\Http\Requests\Teacher;

use App\Enums\ModuleTypeEnum;
use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreModuleRequest extends ApiRequest
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
            'type' => 'required|in:link,content',
            'content' => 'required_if:type,content',
            'link' => 'required_if:type,link|url|max:65000',
            'link_type' => 'required_if:type,link|in:' . implode(',', ModuleTypeEnum::linkType()),
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul materi wajib diisi.',
            'title.string' => 'Judul materi tidak valid.',
            'title.max' => 'Judul materi maksimal :max karakter.',
            'type.required' => 'Tipe materi wajib diisi.',
            'type.in' => 'Tipe materi tidak valid.',
            'content.required_if' => 'Konten materi wajib diisi.',
            'link.required_if' => 'Tautan materi wajib diisi.',
            'link.url' => 'Tautan materi tidak valid.',
            'link.max' => 'Tautan materi maksimal :max karakter.',
            'link_type.required_if' => 'Tipe tautan materi wajib diisi.',
            'link_type.in' => 'Tipe tautan materi tidak valid.',
        ];
    }
}
