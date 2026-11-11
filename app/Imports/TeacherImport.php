<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\Employee;
use App\Models\Religion;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class TeacherImport implements ToModel, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        if ($row[0] === 'NAMA' || empty($row[0] || $row[0] === 'Contoh Format (Jangan Dihapus)')) {
            return null;
        }

        $nip = trim((string) $row[2]);

        $user = User::firstOrCreate(
            ['email' => $row[1]],
            [
                'name' => $row[0],
                'slug' => Str::slug($row[0]),
                'password' => Hash::make($nip),
            ]
        );

        $user->assignRole(RoleEnum::TEACHER->value);

        $birthDate = is_numeric($row[4])
            ? Carbon::instance(Date::excelToDateTimeObject($row[4]))
            : Carbon::parse($row[4]);

        Employee::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nip' => $nip,
                'birth_place' => $row[3],
                'birth_date' => $birthDate,
                'gender' => $row[5] === 'Laki-laki' ? 'male' : 'female',
                'nik' => (string) $row[6],
                'phone_number' => (string) $row[7],
                'religion_id' => Religion::where('name', $row[8])->first()->id,
                'address' => $row[9],
                'status' => RoleEnum::TEACHER->value,
            ]
        );
    }


    public function rules(): array
    {
        return [
            '0' => 'required|max:255',       // nama
            '1' => 'required|email|unique:users,email',  // email
            '2' => 'required|max:18',         // NIP
            '3' => 'required|max:255',        // tempat lahir
            '4' => 'required|numeric',          // tanggal lahir
            '5' => 'required|in:Laki-laki,Perempuan',
            '6' => 'required|max:16',         // NIK
            '7' => 'required|max:15',         // no hp
            '8' => 'required|exists:religions,name',  // agama
            '9' => 'required|max:255',        // alamat
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.required' => 'Nama wajib diisi',
            '0.max' => 'Nama tidak boleh lebih dari 255 karakter',
            '1.required' => 'Email wajib diisi',
            '1.email' => 'Email tidak valid',
            '1.unique' => 'Email sudah digunakan',
            '2.required' => 'NIP wajib diisi',
            '2.max' => 'NIP tidak boleh lebih dari :max karakter',
            '3.required' => 'Tempat lahir wajib diisi',
            '3.max' => 'Tempat lahir tidak boleh lebih dari :max karakter',
            '4.required' => 'Tanggal lahir wajib diisi',
            '4.numeric' => 'Format tanggal lahir tidak valid',
            '5.required' => 'Jenis kelamin wajib diisi',
            '5.in' => 'Jenis kelamin tidak valid',
            '6.required' => 'NIK wajib diisi',
            '6.max' => 'NIK tidak boleh lebih dari :max karakter',
            '7.required' => 'Nomor HP wajib diisi',
            '7.max' => 'Nomor HP tidak boleh lebih dari :max karakter',
            '8.required' => 'Agama wajib diisi',
            '8.exists' => 'Agama tidak terdaftar di sistem',
            '9.required' => 'Alamat wajib diisi',
            '9.max' => 'Alamat tidak boleh lebih dari :max karakter',
        ];
    }
}
