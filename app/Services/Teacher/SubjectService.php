<?php

namespace App\Services\Teacher;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class SubjectService
{
    /**
     * mapping data subject and classroom in subject index
     *
     * @param LengthAwarePaginator $data
     * @param Collection $lessonSchedules
     * @return LengthAwarePaginator
     */
    public function mapPagination(LengthAwarePaginator $data, Collection $lessonSchedules): LengthAwarePaginator
    {
        foreach ($lessonSchedules as $lessonSchedule) {
            foreach($data as $subject) {
                $teacherSubject = $subject->teacherSubjects
                    ->filter(function ($teacherSubject) use ($lessonSchedule) {
                        return $teacherSubject->lessonScheadules
                            ->contains('classroom_id', $lessonSchedule->classroom_id);
                    })
                    ->first();
    
                if ($teacherSubject) {
                    $lessonSchedule = $teacherSubject->lessonScheadules
                        ->where('classroom_id', $lessonSchedule->classroom_id)
                        ->first();

                    $subject->teacher_name = optional($teacherSubject?->employee?->user)->name;
                    $subject->classroom_id = optional($lessonSchedule?->classroom)->id;
                    $subject->classroom_name = optional($lessonSchedule?->classroom)->name;
                    $subject->school_year = optional($lessonSchedule?->classroom?->schoolYear)->school_year;
                    $subject->level_class = optional($lessonSchedule?->classroom?->levelClass)->name;
                    $subject->subject_name = optional($teacherSubject?->subject)->name;
                    $subject->subject_id = optional($teacherSubject?->subject)->id;
                    $subject->total_students = optional($lessonSchedule?->classroom?->classroomStudents)->count();
                    $subject->religion_name = optional($teacherSubject?->subject?->religion)->name;
                }
            }
        }

        return $data;
    }
}
