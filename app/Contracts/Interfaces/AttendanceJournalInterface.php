<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Contracts\Interfaces\Eloquent\WhereInterface;

interface AttendanceJournalInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface, WhereInterface
{
    public function getByClassroomStudent(mixed $id): mixed;
    public function deleteByJournalTeacher(mixed $id) : mixed;
    public function updateByClassroomStudent(mixed $id, array $data) : mixed;
    public function whereLessonSchedule(mixed $id) : mixed;
    public function whereClassroomStudent(mixed $id, mixed $classroom): mixed;
    public function getByTeacherJournal(mixed $id): mixed;
    public function insert(array $data): mixed;
    public function upsert(array $data, array $uniqueBy, array $update): int;
    /**
     * Perform bulk update on ids
     *
     * @param array $ids
     * @param array $data
     * @return mixed
     */
    public function bulkUpdateById(array $ids, array $data): mixed;
}


