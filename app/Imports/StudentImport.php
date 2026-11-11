<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\ClassroomStudent;
use Illuminate\Support\Str;
use App\Models\Religion;
use App\Models\Student;
use App\Enums\RoleEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToModel, WithValidation, SkipsOnFailure
{
    use SkipsFailures;
    private $classroom;

    public function __construct($classroom)
    {
        $this->classroom = $classroom;
    }

    public function model(array $row)
    {
        if ($row[0] == 'NAMA' || $row[0] == null || $row[0] == 'Contoh Format(Jangan Dihapus)') {
            return null;
        }

        $user = User::where('email', $row[1])->first();

        if ($user) {
            return null;
        } else {
            $user = User::create([
                'name' => $row[0] ?? null,
                'email' => $row[1],
                'slug' => Str::slug($row[0]),
                'password' => Hash::make($row[2])
            ]);
        }

        $user->assignRole(RoleEnum::STUDENT->value);

        $birthDate = is_numeric($row[3])
        ? Carbon::instance(Date::excelToDateTimeObject($row[3]))
        : Carbon::parse($row[3]);

        $data = [
            'user_id' => $user->id,
            'nisn'        => $row[2],
            'religion_id' => Religion::where('name', $row[12])->first()->id,
            'gender' => $row[5] == 'Laki-laki' ? 'male' : 'female',
            'birth_date' => $birthDate,
            'birth_place' => $row[4],
            'address' => $row[9],
            'nik' => $row[6],
            'number_kk' => $row[7],
            'number_akta' => $row[8],
            'order_child' => $row[10],
            'count_siblings' => $row[11]
        ];

        if (in_array(null, $data)) {
            $user->delete();
            return null;
        }

        $student_id = Student::create($data)->id;

        ClassroomStudent::create([
            'student_id' => $student_id,
            'classroom_id' => $this->classroom,
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|max:255',       // nama
            '1' => 'required|email|unique:users,email',  // email
            '2' => 'required|max:10',         // nisn
            '3' => 'required|numeric',          // tanggal lahir
            '4' => 'required|max:255',        // tempat lahir
            '5' => 'required|in:Laki-laki,Perempuan',        // jenis kelamin
            '6' => 'required|max:16',         // NIK
            '7' => 'required|max:255',         // nomor KK
            '8' => 'required|max:255',         // nomor akta
            '9' => 'required|max:1000',         // alamat
            '10' => 'required|max:1000|numeric|min:1',        // anak ke
            '11' => 'required|max:1000|numeric|min:0',        // jumlah saudara
            '12' => 'required|exists:religions,name',  // agama
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.required' => 'Nama wajib diisi',
            '0.max' => 'Nama tidak boleh lebih dari :max karakter',
            '1.required' => 'Email wajib diisi',
            '1.email' => 'Email tidak valid',
            '1.unique' => 'Email sudah digunakan',
            '2.required' => 'NISN wajib diisi',
            '2.max' => 'NISN tidak boleh lebih dari :max karakter',
            '3.required' => 'Tanggal lahir wajib diisi',
            '3.numeric' => 'Format tanggal lahir tidak valid',
            '4.required' => 'Tempat lahir wajib diisi',
            '4.max' => 'Tempat lahir tidak boleh lebih dari :max karakter',
            '5.required' => 'Jenis kelamin wajib diisi',
            '5.in' => 'Jenis kelamin tidak valid',
            '6.required' => 'NIK wajib diisi',
            '6.max' => 'NIK tidak boleh lebih dari :max karakter',
            '7.required' => 'Nomor KK wajib diisi',
            '7.max' => 'Nomor KK tidak boleh lebih dari :max karakter',
            '8.required' => 'Nomor akta wajib diisi',
            '8.max' => 'Nomor akta tidak boleh lebih dari :max karakter',
            '9.required' => 'Alamat wajib diisi',
            '9.max' => 'Alamat tidak boleh lebih dari :max karakter',
            '10.required' => 'Anak ke wajib diisi',
            '10.max' => 'Anak ke tidak boleh lebih dari :max karakter',
            '10.numeric' => 'Anak ke harus numerik',
            '11.required' => 'Jumlah saudara wajib diisi',
            '11.max' => 'Jumlah saudara tidak boleh lebih dari :max karakter',
            '11.numeric' => 'Jumlah saudara harus numerik',
            '11.min' => 'Jumlah saudara tidak boleh lebih kecil dari :min',
            '12.required' => 'Agama wajib diisi',
            '12.exists' => 'Agama tidak terdaftar di sistem',
        ];
    }
}
