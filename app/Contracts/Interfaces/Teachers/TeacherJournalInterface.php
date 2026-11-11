<?php

namespace App\Contracts\Interfaces\Teachers;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\PaginateInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Models\TeacherJournal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface TeacherJournalInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface, PaginateInterface
{
    public function updateWithLesson(array $data, mixed $id): mixed;
    public function getLessonSchedule(mixed $id) : mixed;
    public function histories(mixed $id,Request $request): mixed;
    public function whereTeacher(mixed $employee_id, mixed $subject_id): mixed;
    public function getByTeacher(mixed $employee_id): mixed;
    public function getJournalToday(mixed $id): mixed;
    public function getAllJournalByUser(mixed $id, Request $request);
    public function getByLessonSchedule(mixed $id): mixed;
    public function getByClassroomAndSubject(string $classroomId, string $subjectId, string $semester, Request $request): Collection;
    public function getStudentAttendance(string $id): ?TeacherJournal;
}
