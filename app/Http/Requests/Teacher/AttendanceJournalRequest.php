<?php

namespace App\Http\Requests\Teacher;

use App\Enums\AttendanceEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceJournalRequest extends FormRequest
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
        $required = [
            'attendances' =>  ['array', 'required', 'min:1'],
            'attendances.*.status' => [
                'required',
                Rule::in(
                    array_column(AttendanceEnum::cases(), 'value')
                )
            ]
        ];

        if($this->isMethod('post')){
            $required['attendances.*.classroom_student_id'] = ['required', 'exists:classroom_students,id'];
            return $required;
        }

        $required['attendances.*.id'] = ['required', 'exists:attendance_journals,id'];

        return $required;
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $validStatuses = implode(', ', array_column(AttendanceEnum::cases(), 'value'));

        return [
            'attendances.required' => 'Data kehadiran wajib diisi.',
            'attendances.array' => 'Data kehadiran harus dalam bentuk array.',
            'attendances.min' => 'Minimal satu data kehadiran harus disubmit.',

            'attendances.*.status.required' => 'Status kehadiran wajib diisi untuk setiap siswa.',
            'attendances.*.status.in' => "Status kehadiran harus salah satu dari: $validStatuses.",

            'attendances.*.classroom_student_id.required' => 'ID siswa di kelas wajib diisi untuk setiap siswa.',
            'attendances.*.classroom_student_id.exists' => 'Siswa di kelas yang dipilih tidak ditemukan.',

            'attendances.*.id.required' => 'ID kehadiran wajib diisi untuk setiap siswa.',
            'attendances.*.id.exists' => 'Data kehadiran yang dipilih tidak ditemukan.',
        ];
    }
}
