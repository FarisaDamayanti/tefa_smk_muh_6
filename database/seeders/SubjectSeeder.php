<?php

namespace Database\Seeders;

use App\Models\Religion;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'Matematika',
            'PJOK',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Bahasa Jawa',
            'Pendidikan Agama Islam',
            'Pendidikan Agama Kristen',
            'Pendidikan Agama Katolik',
            'Pendidikan Agama Hindu',
            'Pendidikan Agama Budha',
            'Pendidikan Agama Konghucu',
            'Seni Budaya',
            'Ilmu Pengetahuan Alam',
            'Ilmu Pengetahuan Sosial',
            'Pendidikan Kewarganegaran',
            'Informatika'
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'name' => $subject,
                'religion_id' => ($subject == 'Pendidikan Agama Islam' ? $this->getByReligionName('Islam')->id : ($subject == 'Pendidikan Agama Kristen' ? $this->getByReligionName('Kristen')->id : ($subject == 'Pendidikan Agama Katolik' ? $this->getByReligionName('Katolik')->id : ($subject == 'Pendidikan Agama Hindu' ? $this->getByReligionName('Hindu')->id : ($subject == 'Pendidikan Agama Budha' ? $this->getByReligionName('Budha')->id : ($subject == 'Pendidikan Agama Konghucu' ? $this->getByReligionName('Konghucu')->id : null)))))),
            ]);
        }
    }

    public function getByReligionName($name)
    {
        return Religion::where('name', $name)->first();
    }
}
