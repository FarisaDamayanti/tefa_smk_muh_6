<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Models\Religion;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = User::where('email', 'student@gmail.com')->first();

        $religion = Religion::first();

        Student::create([
            'user_id' => $student->id,
            'nisn' => '1234567890',
            'nik' => '12345678910123',
            'religion_id' => $religion->id,
            'gender' => GenderEnum::FEMALE->value,
            'birth_date' => now(),
            'birth_place' => 'Malang',
            'address' => 'Permata Regency',
            'number_kk' => '123456789101234',
            'number_akta' => '1234567891012',
            'order_child' => '2',
            'count_siblings' => '3'
        ]);
    }
}
