<?php

namespace App\Http\Controllers\Schools;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\DeleteClassroomStudentRequest;
use App\Http\Requests\School\StoreClassroomStudentRequest;
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

            return ResponseHelper::success($request->validated(), 'Berhasil menambahkan siswa.');
        } catch (\Throwable $th) {
            return ResponseHelper::error('Gagal menambahkan siswa. ' . $th->getMessage());
        }
    }

    public function destroy(DeleteClassroomStudentRequest $request)
    {
        try {
            foreach($request->ids as $id) {
                $this->classroomStudent->delete($id);
            }

            return ResponseHelper::success($request->validated(), 'Berhasil menghapus siswa.');
        } catch (\Throwable $th) {
            return ResponseHelper::error('Gagal menghapus siswa.' . $th->getMessage());
        }
    }
}
