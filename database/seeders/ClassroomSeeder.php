<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Employee;
use App\Models\LevelClass;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::first();
        $student = Student::first();

        $levelClass = LevelClass::where('name', '11')->first();
        $schoolYear = SchoolYear::where('active', 1)->first();

        $classroom = Classroom::create([
            'name' => 'Rpl 1',
            'employee_id' => $employee->id,
            'school_year_id' => $schoolYear->id,
            'level_class_id' => $levelClass->id,
        ]);

        ClassroomStudent::create([
            'id' => '1',
            'student_id' => $student->id,
            'classroom_id' => $classroom->id,
        ]);
    }
}
