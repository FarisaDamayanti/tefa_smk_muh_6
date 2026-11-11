<?php

namespace App\Http\Requests\School;

use App\Contracts\Repositories\GraduationRepository;
use Illuminate\Foundation\Http\FormRequest;

class GraduationRequest extends FormRequest
{
    protected GraduationRepository $graduation;

    public function __construct(GraduationRepository $graduation)
    {
        parent::__construct();
        $this->graduation = $graduation;
    }

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
            'announcement_date' => 'required|date|after:now',
            'additional_file' => 'nullable|file|mimes:pdf,docx|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'announcement_date.required' => 'Tanggal pengumuman wajib diisi.',
            'announcement_date.date' => 'Tanggal pengumuman harus berupa tanggal.',
            'announcement_date.after' => 'Tanggal pengumuan tidak boleh masa lalu.',
            'additional_file.file' => 'File tambahan harus berupa file.',
            'additional_file.mimes' => 'File tambahan harus berupa file PDF atau DOCX.',
            'additional_file.max' => 'File tambahan tidak boleh lebih dari 10MB.',
        ];
    }
}
