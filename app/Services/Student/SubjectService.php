<?php

namespace App\Services\Student;

use App\Traits\PaginationTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class SubjectService
{
    use PaginationTrait;

    /**
     * mapping data subject and classroom in subject index
     *
     * @param Collection $data
     * @param string $classroom_id
     * @return LengthAwarePaginator
     */
    public function mapPagination(Collection $data, string $classroom_id): LengthAwarePaginator
    {
        $newCollection = collect();

        foreach ($data as $subject) {

            $teacherSubjects = $subject->teacherSubjects
                ->filter(function ($teacherSubject) use ($classroom_id) {
                    return $teacherSubject->lessonScheadules
                        ->contains('classroom_id', $classroom_id);
                });

            foreach ($teacherSubjects as $teacherSubject) {

                $lessonSchedule = $teacherSubject->lessonScheadules
                    ->where('classroom_id', $classroom_id)
                    ->first();

                $newItem = clone $subject;

                $newItem->teacher_name = optional($teacherSubject?->employee?->user)->name;
                $newItem->classroom_id = optional($lessonSchedule?->classroom)->id;
                $newItem->classroom_name = optional($lessonSchedule?->classroom)->name;
                $newItem->school_year = optional($lessonSchedule?->classroom?->schoolYear)->school_year;
                $newItem->level_class = optional($lessonSchedule?->classroom?->levelClass)->name;
                $newItem->subject_name = optional($teacherSubject?->subject)->name;
                $newItem->subject_id = optional($teacherSubject?->subject)->id;
                $newItem->religion_name = optional($teacherSubject?->subject?->religion)->name;

                $newCollection->push($newItem);
            }
        }

        return $this->paginateCollection(
            $newCollection,
            request()->query('per_page', 10),
            Paginator::resolveCurrentPage(),
            [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]
        );
    }
}
