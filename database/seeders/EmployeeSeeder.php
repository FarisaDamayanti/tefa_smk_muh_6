<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Enums\RoleEnum;
use App\Models\Employee;
use App\Models\Religion;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = User::where('email', 'teacher@gmail.com')->first();
        $staff = User::where('email', 'staff@gmail.com')->first();

        $religion = Religion::first();

        Employee::create([
            'nip' => '123456789101234567',
            'birth_date' => now(),
            'birth_place' => 'Malang',
            'gender' => GenderEnum::MALE->value,
            'nik' => '12345678910123',
            'phone_number' => '00000000000',
            'address' => 'Permata Regency',
            'status' => RoleEnum::TEACHER->value,
            'active' => '1',
            'user_id' => $teacher->id,
            'religion_id' => $religion->id,
        ]);

        Employee::create([
            'nip' => '123456789101234567',
            'birth_date' => now(),
            'birth_place' => 'Malang',
            'gender' => GenderEnum::MALE->value,
            'nik' => '12345678910123',
            'phone_number' => '00000000000',
            'address' => 'Permata Regency',
            'status' => RoleEnum::STAFF->value,
            'active' => '1',
            'user_id' => $staff->id,
            'religion_id' => $religion->id,
        ]);
    }
}
