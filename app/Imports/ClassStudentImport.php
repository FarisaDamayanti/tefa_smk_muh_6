<?php

namespace App\Imports;

use App\Models\ClassroomStudent;
use App\Models\Classroom;
use App\Models\Religion;
use App\Enums\RoleEnum;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\BeforeSheet;
use Illuminate\Support\Facades\Hash;

class ClassStudentImport implements ToModel, WithHeadingRow, WithEvents
{
    protected $sheetName;
    public $errors = [];

    public function model(array $row)
    {
        if (
            empty(trim((string) ($row['nama'] ?? ''))) &&
            empty(trim((string) ($row['email'] ?? '')))
        ) {
            return null;
        }

        // Abaikan sheet petunjuk
        if (trim($this->sheetName ?? '') === 'Petunjuk') {
            return null;
        }

        // Ambil tingkat dan nama kelas dari nama sheet
        $sheetClassroom = explode(' - ', $this->sheetName, 2);

        if (count($sheetClassroom) !== 2) {
            $this->errors[] = "Format nama sheet tidak sesuai. Nama sheet terbaca: " . $this->sheetName;
            return null;
        }

        $levelName = trim($sheetClassroom[0]);
        $className = trim($sheetClassroom[1]);

        // Ubah tingkat Romawi menjadi angka
        $levelMap = [
            'X' => '10',
            'XI' => '11',
            'XII' => '12',
        ];

        $levelName = $levelMap[$levelName] ?? $levelName;

        // RPL 1 menjadi RPL
        $className = preg_replace('/\s+\d+$/', '', $className);
        
        // Cari kelas berdasarkan nama dan tingkat
        $classroom = Classroom::where('name', $className)
            ->whereHas('levelClass', function ($query) use ($levelName) {
                $query->where('name', $levelName);
            })
            ->latest()
            ->first();

        if (!$classroom) {
            $this->errors[] = "Kelas {$className} tingkat {$levelName} tidak ditemukan.";
            return null;
        }

        $class_id = $classroom->id;

        // Periksa akun berdasarkan email
        $user = User::where('email', $row['email'] ?? null)->first();

        // Jika akun sudah memiliki data siswa, lewati
        if ($user && $user->student) {
            return null;
        }

        // Validasi kolom wajib
        $requiredFields = [
            'email' => 'Email',
            'nisn' => 'NISN',
            'tanggal_lahir' => 'Tanggal lahir',
            'tempat_lahir' => 'Tempat lahir',
            'jenis_kelamin' => 'Jenis kelamin',
            'nik' => 'NIK',
            'nomor_kk' => 'Nomor KK',
            'nomor_akta' => 'Nomor Akta',
            'alamat' => 'Alamat',
            'anak_ke' => 'Anak ke',
            'jumlah_saudara' => 'Jumlah saudara',
            'agama' => 'Agama',
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty(trim((string) ($row[$field] ?? '')))) {
                $this->errors[] = "{$label} kosong untuk {$row['nama']}, silahkan isi terlebih dahulu";
                return null;
            }
        }

        // Periksa tanggal lahir
        if (!is_numeric($row['tanggal_lahir'])) {
            $this->errors[] = "Tanggal lahir tidak valid untuk {$row['nama']}";
            return null;
        }

        try {
            $birthDate = Carbon::instance(
                Date::excelToDateTimeObject($row['tanggal_lahir'])
            )->format('Y-m-d');
        } catch (\Throwable $th) {
            $this->errors[] = "Tanggal lahir tidak dapat diproses untuk {$row['nama']}";
            return null;
        }

        // Cari agama
        $religionName = trim($row['agama']);

        if (strtolower($religionName) === 'buddha') {
            $religionName = 'Budha';
        }

        $religionId = Religion::where('name', $religionName)->value('id');

        if (!$religionId) {
            $this->errors[] = "Agama {$religionName} tidak ditemukan untuk {$row['nama']}";
            return null;
        }

        // Buat akun jika belum tersedia
        $newUser = false;

        if (!$user) {
            $user = User::create([
                'name' => $row['nama'],
                'email' => $row['email'],
                'slug' => Str::slug($row['nama']),
                'password' => Hash::make($row['nisn']),
            ]);

            $newUser = true;
        }

        try {
            $data = [
                'user_id' => $user->id,
                'nisn' => $row['nisn'],
                'religion_id' => $religionId,
                'gender' => $row['jenis_kelamin'] == 'Laki-laki' ? 'male' : 'female',
                'birth_date' => $birthDate,
                'birth_place' => $row['tempat_lahir'],
                'address' => $row['alamat'],
                'nik' => $row['nik'],
                'number_kk' => $row['nomor_kk'],
                'number_akta' => $row['nomor_akta'],
                'order_child' => $row['anak_ke'],
                'count_siblings' => $row['jumlah_saudara'],
            ];
            
            if (in_array(null, $data, true)) {
                throw new \Exception('Ada data siswa yang masih kosong.');
            }

            // Berikan role siswa
            $user->assignRole(RoleEnum::STUDENT->value);

            // Simpan data siswa
            $student = Student::create($data);

            // Hubungkan siswa dengan kelas
            ClassroomStudent::create([
                'student_id' => $student->id,
                'classroom_id' => $class_id,
            ]);

        } catch (\Throwable $th) {
            // Hapus akun hanya jika akun tersebut baru dibuat
            if ($newUser) {
                $user->delete();
            }

            throw $th;
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->sheetName = $event->getSheet()->getTitle();
            },
        ];
    }
}
