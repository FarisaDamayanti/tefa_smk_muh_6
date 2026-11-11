<?php

namespace App\Http\Requests\School;

use App\Contracts\Repositories\QuestionBankRepository;
use App\Enums\SemesterEnum;
use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends ApiRequest
{
    protected QuestionBankRepository $questionBankRepository;

    public function __construct(QuestionBankRepository $questionBankRepository)
    {
        parent::__construct();
        $this->questionBankRepository = $questionBankRepository;
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
        $exam = $this->route('exam');

        if ($exam && $exam->studentExams()->exists()) {
            return [
                // 'exam_date' => 'required|date_format:Y-m-d H:i',
                'duration' => 'required|integer|min:1',
            ];
        }

        return [
            'title' => 'required|string|max:255',
            'exam_date' => 'required|date_format:Y-m-d H:i',
            'semester' => 'required|in:' . SemesterEnum::GANJIL->value . ',' . SemesterEnum::GENAP->value,
            'duration' => 'required|integer',
            'minimum_score' => 'required|between:0,100',
            'rules' => 'nullable',
            'question_bank_id' => 'required|exists:question_banks,id',
            'school_year_id' => 'required|exists:school_years,id',
            'total_questions' => 'required|integer|min:0',
            'classroom_ids' => 'required|array|min:1',
            'classroom_ids.*' => 'required|exists:classrooms,id',
            'allow_early_submission' => 'required|boolean',
            'can_view_answer_result' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul ujian wajib diisi.',
            'title.string' => 'Judul ujian harus berupa teks.',
            'title.max' => 'Judul ujian maksimal 255 karakter.',
            'exam_date.required'    => 'Tanggal dan waktu ujian wajib diisi.',
            'exam_date.date_format' => 'Format tanggal dan waktu ujian harus: YYYY-MM-DD HH:MM.',
            'semester.required' => 'Semester wajib dipilih.',
            'semester.in' => 'Semester tidak valid. Pilih GANJIL atau GENAP.',
            'duration.required' => 'Durasi ujian wajib diisi.',
            'duration.integer' => 'Durasi ujian harus berupa angka (menit).',
            'duration.min' => 'Durasi ujian minimal :min (menit)',
            'minimum_score.required' => 'Nilai minimal (KKM) wajib diisi.',
            'minimum_score.between'  => 'Nilai minimal harus berada di antara 0 sampai 100.',
            'question_bank_id.required' => 'Bank soal wajib dipilih.',
            'question_bank_id.exists' => 'Bank soal yang dipilih tidak ditemukan.',
            'school_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'school_year_id.exists' => 'Tahun ajaran yang dipilih tidak valid.',
            'total_questions.required' => 'Total soal wajib diisi.',
            'total_questions.integer' => 'Total soal harus berupa angka.',
            'total_questions.min' => 'Total soal tidak boleh minus.',
            'classroom_ids.required' => 'Kelas yang dapat mengikuti ujian wajib dipilih.',
            'classroom_ids.array' => 'Kelas yang dapat mengikuti ujian tidak valid.',
            'classroom_ids.min' => 'Minimal 1 kelas yang dapat mengikuti ujian.',
            'classroom_ids.*.required' => 'Kelas yang dapat mengikuti ujian wajib dipilih.',
            'classroom_ids.*.exists' => 'Kelas yang dapat mengikuti ujian tidak valid.',
            'allow_early_submission.required' => 'Izinkan pengumpulan kapan saja wajib diisi.',
            'allow_early_submission.boolean' => 'Izinkan pengumpulan kapan saja hanya bisa true atau false.',
            'can_view_answer_result.required' => 'Izinkan siswa melihat jawaban benar dan salah wajib diisi.',
            'can_view_answer_result.boolean' => 'Izinkan siswa melihat jawaban benar dan salah hanya bisa true false.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!$this->question_bank_id || !$this->total_questions) {
                return;
            }

            // Ambil jumlah soal via repository
            $questionCount = $this->questionBankRepository
                ->show($this->question_bank_id)->examQuestions()->where('is_active', 1)->count();

            if ($this->total_questions > $questionCount) {
                $validator->errors()->add(
                    'total_questions',
                    "Total soal tidak boleh melebihi jumlah soal pada bank soal ({$questionCount} soal)."
                );
            }
        });
    }
}
