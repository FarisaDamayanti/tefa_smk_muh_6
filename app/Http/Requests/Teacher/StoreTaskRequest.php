<?php

namespace App\Http\Requests\Teacher;

use App\Enums\AnswerOptionEnum;
use App\Enums\SubmissionTypeEnum;
use App\Http\Requests\ApiRequest;

class StoreTaskRequest extends ApiRequest
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
        $isMultipleChoice = $this->input('is_multiple_choice');

        $baseRules = [
            'title' => 'required|max:255',
            'description' => 'required|max:60000',
            'deadline' => 'required|date|after:now',
            'allow_overdue' => 'required|boolean',
            'is_multiple_choice' => 'required|boolean',
        ];

        if ($isMultipleChoice) {
            $validate = [
                'can_view_answer_result' => 'required|boolean',
                'task_questions' => 'required|array|min:1',
                'task_questions.*.question' => 'required|string|max:4294967295',
                'task_questions.*.options' => 'required|array|min:1',
                'task_questions.*.options.*.option_label' => 'required|in:' . 
                    AnswerOptionEnum::join(
                        AnswerOptionEnum::OPTION_A,
                        AnswerOptionEnum::OPTION_B,
                        AnswerOptionEnum::OPTION_C,
                        AnswerOptionEnum::OPTION_D,
                        AnswerOptionEnum::OPTION_E
                    ),
                'task_questions.*.options.*.option_text' => 'required|string|max:4294967295',
                'task_questions.*.options.*.is_correct' => 'required|boolean',
            ];
        } else {
            $validate = [
                'submission_type' => 'required|in:' . 
                    SubmissionTypeEnum::FILE->value . ',' . 
                    SubmissionTypeEnum::LINK->value,
            ];
        }

        return array_merge($baseRules, $validate);
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul tugas wajib diisi.',
            'title.max' => 'Judul tugas maksimal :max karakter.',

            'description.required' => 'Deskripsi tugas wajib diisi.',
            'description.max' => 'Deskripsi tugas maksimal :max karakter.',

            'deadline.required' => 'Tenggat wajib diisi.',
            'deadline.date' => 'Tenggat tidak valid.',
            'deadline.after' => 'Tenggat harus lebih dari waktu sekarang.',

            'allow_overdue.required' => 'Izin pengumpulan terlambat wajib diisi.',
            'allow_overdue.boolean' => 'Izin pengumpulan terlambat tidak valid.',

            'is_multiple_choice.required' => 'Tipe tugas wajib diisi.',
            'is_multiple_choice.boolean' => 'Tipe tugas tidak valid.',

            'submission_type.required' => 'Jenis pengumpulan wajib diisi.',
            'submission_type.in' => 'Jenis pengumpulan tidak valid.',

            'can_view_answer_result.required' => 'Pengaturan lihat hasil wajib diisi.',
            'can_view_answer_result.boolean' => 'Pengaturan lihat hasil tidak valid.',

            'task_questions.required' => 'Soal wajib diisi.',
            'task_questions.array' => 'Format soal tidak valid.',
            'task_questions.min' => 'Minimal harus ada 1 soal.',

            'task_questions.*.question.required' => 'Pertanyaan wajib diisi.',
            'task_questions.*.question.string' => 'Pertanyaan tidak valid.',
            'task_questions.*.question.max' => 'Pertanyaan maksimal :max karakter.',

            'task_questions.*.options.required' => 'Pilihan jawaban wajib diisi.',
            'task_questions.*.options.array' => 'Format pilihan jawaban tidak valid.',
            'task_questions.*.options.min' => 'Minimal harus ada 1 pilihan jawaban.',

            'task_questions.*.options.*.option_label.required' => 'Label pilihan wajib diisi.',
            'task_questions.*.options.*.option_label.in' => 'Label pilihan tidak valid.',

            'task_questions.*.options.*.option_text.required' => 'Isi pilihan wajib diisi.',
            'task_questions.*.options.*.option_text.string' => 'Isi pilihan tidak valid.',
            'task_questions.*.options.*.option_text.max' => 'Isi pilihan maksimal :max karakter.',

            'task_questions.*.options.*.is_correct.required' => 'Status jawaban benar wajib diisi.',
            'task_questions.*.options.*.is_correct.boolean' => 'Status jawaban benar tidak valid.',
        ];
    }
}
