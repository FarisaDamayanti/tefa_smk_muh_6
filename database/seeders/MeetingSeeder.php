<?php

namespace Database\Seeders;

use App\Enums\SemesterEnum;
use App\Models\LessonSchedule;
use App\Models\TeacherJournal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessonSchedule = LessonSchedule::first();
        TeacherJournal::create(
           [
            'lesson_schedule_id' => $lessonSchedule->id,
            'title' => 'Pertemuan 1: Pengenalan Materi',
            'description' => 'Pengenalan materi pelajaran dan tujuan pembelajaran.',
            'date' => now(),
            'meeting' => 1,
            'semester' => SemesterEnum::GENAP->value,
           ]
        );
    }
}
