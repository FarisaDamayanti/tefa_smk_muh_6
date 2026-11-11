<?php

namespace App\Services\Teacher;

use App\Contracts\Repositories\TaskSubmissionRepository;
use App\Services\Teacher\TeacherModuleService;
use App\Http\Requests\Teacher\StoreTaskSubmissionGradeRequest;
use Illuminate\Support\Facades\DB;

class TaskSubmissionService
{
    public function __construct
    (
        private TaskSubmissionRepository $taskSubmission,
    ){}

    /**
     * logic update grade with looping
     * 
     * @param StoreTaskSubmissionRequest $request
     * @return void
     */
    public function update(StoreTaskSubmissionGradeRequest $request): void
    {
        $data = $request->validated()['scores'];

        $ids = [];
        $cases = 'CASE id ';

        foreach ($data as $item) {
            $grade = is_null($item['grade']) ? 'NULL' : (int) $item['grade'];
            $id = addslashes($item['id']);
            $ids[] = $id;
            $cases .= "WHEN '{$id}' THEN {$grade} ";
        }

        $cases .= 'END';

        $this->taskSubmission->bulkUpdate($ids, ['grade' => DB::raw($cases)]);
    }
}
