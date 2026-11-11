<?php

namespace App\Http\Controllers\Api\Student;

use App\Contracts\Repositories\ModuleRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleResource;
use Illuminate\Http\Request;
use Throwable;

class ModuleController extends Controller
{

    public function __construct(
        protected ModuleRepository $moduleRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $modules = $this->moduleRepository->whereStudent(auth_student()->id);
            return ResponseHelper::success(ModuleResource::collection($modules), message('alert.fetch_success'));
        } catch(Throwable $th){
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }
}
