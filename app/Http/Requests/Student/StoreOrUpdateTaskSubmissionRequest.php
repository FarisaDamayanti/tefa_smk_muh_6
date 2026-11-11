<?php

namespace App\Http\Requests\Student;

use App\Enums\SubmissionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrUpdateTaskSubmissionRequest extends FormRequest
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
        $isImageSubmission = $this->getSubmissionType()->value === SubmissionTypeEnum::FILE->value;
        
        return [
            'answer' => [
                'required',
                Rule::when(
                    $isImageSubmission, 
                    ['file', 'max:10240', 'mimes:zip,rar'], 
                    ['url', 'max:255']
                ),
            ]
        ];
    }

    public function messages()
    {
        $type = $this->getSubmissionType()->value;

        $data = $type == SubmissionTypeEnum::FILE->value 
            ? [
                'answer.required' => 'File tugas wajib diupload.',
                'answer.file' => 'File harus berupa file.',
                'answer.max' => 'File tugas maksimal 10 MB.',
                'answer.mimes' => 'File harus berupa ZIP atau RAR.',
            ]
            : [
                'answer.required' => 'Link tugas wajib diisi.',
                'answer.url' => 'Tugas harus berupa URL.',
                'answer.max' => 'Tugas maksimal 255 karakter.',
            ];

        return $data;
    }

    private function getSubmissionType()
    {
        return $this->route('task')->submission_type;
    }
}
