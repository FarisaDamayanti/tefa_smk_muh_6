<?php

namespace App\Http\Controllers\Api\School;

use App\Contracts\Repositories\ClassroomRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function __construct
    (
        private ClassroomRepository $classroom,
    ){}
    
    /**
     * get by level class and school year
     *
     * @param string $levelClass
     * @param string $schoolYear
     * @return JsonResponse
     */
    public function getByLevelClassAndSchoolYear(string $levelClass, string $schoolYear): JsonResponse
    {
        try {
            $classrooms = $this->classroom->getByLevelClassAndSchoolYear($levelClass, $schoolYear);

            return ResponseHelper::success($classrooms, message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }
}
