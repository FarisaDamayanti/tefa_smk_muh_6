<?php

namespace App\Services\School;

use App\Contracts\Repositories\ClassroomRepository;
use Illuminate\Http\Request;

class StudentService
{
    public function __construct
    (
        private ClassroomRepository $classroom,
    ){}

    /**
     * mapping data for export
     * 
     * @param Request $request
     * @return array
     */
    public function export(Request $request): array
    {
        $classrooms = $this->classroom->getByFilterWithRelation($request);

        $sheets = [];

        foreach ($classrooms as $classroom) {
            $students = $classroom->classroomStudents;

            $rows = [];

            foreach ($students as $classroomStudent) {
                $student = $classroomStudent->student;

                $rows[] = [
                    'nisn'  => $student->nisn,
                    'name'  => $student->user->name,
                    'email' => $student->user->email,
                ];
            }

            $sheets[] = [
                'sheet_name' => $classroom->levelClass->name . ' ' . $classroom->name,
                'rows' => $rows,
            ];
        }

        return $sheets;
    }
}
