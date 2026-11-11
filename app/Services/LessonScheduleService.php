<?php

namespace App\Services;

use App\Contracts\Interfaces\LessonHourInterface;
use App\Contracts\Interfaces\LessonScheduleInterface;
use App\Contracts\Interfaces\SchoolYearInterface;
use App\Contracts\Interfaces\TeacherSubjectInterface;
use App\Http\Requests\StoreLessonScheduleRequest;
use App\Http\Requests\UpdateLessonScheduleRequest;
use App\Models\Classroom;
use App\Models\LessonSchedule;
use Exception;

class LessonScheduleService
{
    private LessonScheduleInterface $lessonSchedule;
    private LessonHourInterface $lessonHour;
    private TeacherSubjectInterface $teacherSubject;
    private SchoolYearInterface $schoolYear;

    public function __construct(LessonHourInterface $lessonHour, TeacherSubjectInterface $teacherSubject, SchoolYearInterface $schoolYear, LessonScheduleInterface $lessonSchedule)
    {
        $this->lessonHour = $lessonHour;
        $this->teacherSubject = $teacherSubject;
        $this->schoolYear = $schoolYear;
        $this->lessonSchedule = $lessonSchedule;
    }

    public function get()
    {
        $days = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];

        $data = [];
        foreach ($days as $day ) {
            $data[] = [
                $this->lessonSchedule->groupByLatest($day)
            ];
        }

        return $data;
    }

    public function store(StoreLessonScheduleRequest $request, Classroom $classroom, string $day): void
    {
        $rules = $request->validated();

        $subject = $this->teacherSubject->whereTeacher($rules['subject_id'], $rules['employee_id']);
        $year = $this->schoolYear->active();

        $start = $this->lessonHour->show($rules['lesson_hour_start']);
        $end = $this->lessonHour->show($rules['lesson_hour_end']);

        $data = [
            'classroom_id' => $classroom->id,
            'lesson_hour_start' => $rules['lesson_hour_start'],
            'lesson_hour_end' => $rules['lesson_hour_end'],
            'teacher_subject_id' => $subject->id,
            'school_year_id' => $year->id,
            'day' => $day
        ];

        if ($this->lessonSchedule->existsOtherSchedule(
            $data['day'],
            $start->start,
            $end->end,
            $data['teacher_subject_id'],
            $data['school_year_id'])
        ){
            throw new Exception("Guru ini memiliki jadwal dikelas lain.");
        }

        $this->lessonSchedule->store($data);
    }

    public function update(UpdateLessonScheduleRequest $request, LessonSchedule $lessonSchedule): void
    {
        $rules = $request->validated();

        $subject = $this->teacherSubject->whereTeacher($rules['subject_id'], $rules['employee_id']);

        $start = $this->lessonHour->show($rules['lesson_hour_start']);
        $end = $this->lessonHour->show($rules['lesson_hour_end']);

        if ($this->lessonSchedule->existsOtherSchedule(
            $lessonSchedule->day,
            $start->start,
            $end->end,
            $subject->id,
            $lessonSchedule->school_year_id)
        ){
            throw new Exception("Guru ini memiliki jadwal dikelas lain.");
        }

        $lessonSchedule->update([
            'lesson_hour_start' => $rules['lesson_hour_start'],
            'lesson_hour_end' => $rules['lesson_hour_end'],
            'teacher_subject_id' => $subject->id,
        ]);
    }
}
