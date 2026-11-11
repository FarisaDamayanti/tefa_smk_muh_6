<?php

namespace App\Http\Requests\School;

use App\Contracts\Repositories\ClassroomRepository;
use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Enums\GraduationStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class GraduationDetailRequest extends FormRequest
{
    private ClassroomStudentRepository $classroomStudentRepository;
    private ClassroomRepository $classroomRepository;
    private SchoolYearRepository $schoolYearRepository;

    public function __construct(ClassroomStudentRepository $classroomStudentRepository, ClassroomRepository $classroomRepository, SchoolYearRepository $schoolYearRepository)
    {
        parent::__construct();
        $this->classroomStudentRepository = $classroomStudentRepository;
        $this->classroomRepository = $classroomRepository;
        $this->schoolYearRepository = $schoolYearRepository;
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
        $activeSchoolYear = $this->schoolYearRepository->active();
        $classrooms = $this->classroomRepository->getByClassLevelAndSchoolYear(12, $activeSchoolYear->id);
        $studentCount = $this->classroomStudentRepository->getStudentGraduatesCount($classrooms->pluck('id')->toArray());

        return [
            'graduates' => 'required|array|min:' . $studentCount . '|max:' . $studentCount,
            'graduates.*.student_id' => 'required|exists:students,id',
            'graduates.*.status' => 'required|in:' . GraduationStatusEnum::join(GraduationStatusEnum::PASSED, GraduationStatusEnum::NOT_PASSED),
        ];
    }

    public function messages()
    {
        return [
            'graduates.required' => 'Pilih status kelulusan di semua siswa.',
            'graduates.array' => 'Format status kelulusan tidak valid.',
            'graduates.min' => 'Terdapat siswa yang belum ditentukan status kelulusannya.',
            'graduates.max' => 'Format status kelulusan terdapat duplikasi.',
            'graduates.*.student_id.required' => 'Siswa tidak ditemukan.',
            'graduates.*.student_id.exists' => 'Siswa tidak valid.',
            'graduates.*.status.required' => 'Terdapat siswa yang belum ditentukan status kelulusannya.',
            'graduates.*.status.in' => 'Status kelulusan tidak valid.',
        ];
    }
}
