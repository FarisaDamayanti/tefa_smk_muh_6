<?php

namespace App\Http\Controllers\Schools;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\DeleteClassroomStudentRequest;
use App\Http\Requests\School\StoreClassroomStudentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $addedCount = 0;
            $skippedStudents = [];

            DB::transaction(function () use (
                $request,
                $classroom,
                &$addedCount,
                &$skippedStudents
            ) {
                foreach ($request->student_ids as $student_id) {

                    // Periksa apakah siswa sudah terdaftar di kelas tujuan
                    $existing = $this->classroomStudent->check(
                        $classroom,
                        $student_id
                    );

                    if ($existing) {
                        $skippedStudents[] = [
                            'id' => $student_id,
                            'name' => $existing->student?->user?->name
                                ?? 'Nama tidak tersedia',
                        ];

                        continue;
                    }

                    // Tambahkan siswa jika belum terdaftar
                    $this->classroomStudent->store([
                        'student_id' => $student_id,
                        'classroom_id' => $classroom,
                    ]);

                    $addedCount++;
                }
            });

            return ResponseHelper::success([
                'added_count' => $addedCount,
                'skipped_count' => count($skippedStudents),
                'skipped_students' => $skippedStudents,
            ], 'Berhasil memproses siswa.');

        } catch (\Throwable $th) {
            return ResponseHelper::error(
                'Gagal menambahkan siswa. ' . $th->getMessage()
            );
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
