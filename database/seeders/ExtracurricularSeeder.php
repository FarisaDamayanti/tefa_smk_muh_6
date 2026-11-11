<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Extracurricular;
use App\Models\ExtracurricularStudent;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Faker\Provider\Uuid;

class ExtracurricularSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::first();
        $student = Student::first();

        $extracurricular = Extracurricular::create([
            'id' => Uuid::uuid(),
            'name' => 'Sepak Bola',
            'employee_id' => $employee->id,
        ]);

        ExtracurricularStudent::create([
            'student_id' => $student->id,
            'extracurricular_id' => $extracurricular->id,
        ]);
    }
}
