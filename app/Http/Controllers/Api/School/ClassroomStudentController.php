<?php

namespace App\Http\Controllers\Api\School;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\DeleteClassroomStudentRequest;
use App\Http\Requests\School\StoreClassroomStudentRequest;
use App\Models\Classroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassroomStudentController extends Controller
{
    public function __construct
    (
        private ClassroomStudentRepository $classroomStudent,
    ){}

    /**
     * store student in the classroom
     *
     * @param StoreClassroomStudentRequest $request
     * @param string $classroom
     * @return JsonResponse
     */
    public function store(StoreClassroomStudentRequest $request, string $classroom): JsonResponse
    {
        try {
            foreach($request->student_ids as $student_id) {
                $this->classroomStudent->store([
                    'student_id' => $student_id,
                    'classroom_id' => $classroom,
                ]);
            }

            return ResponseHelper::success($request->validated(), message('alert.add_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    public function destroy(DeleteClassroomStudentRequest $request)
    {
        try {
            foreach($request->ids as $id) {
                $this->classroomStudent->delete($id);
            }

            return ResponseHelper::success($request->validated(), message('alert.update_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }
}
