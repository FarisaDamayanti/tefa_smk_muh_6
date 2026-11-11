<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\ModuleRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreModuleRequest;
use App\Http\Requests\Teacher\UpdateModuleRequest;
use App\Http\Resources\ModuleDetailResource;
use App\Models\Module;
use App\Models\Topic;
use App\Services\Teacher\ModuleService;
use App\Traits\UploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ModuleController extends Controller
{
    use UploadTrait;

    public function __construct
    (
        private ModuleRepository $module,
        private ModuleService $service,
    ){}

    /**
     * Store a newly created resource in storage.
     * 
     * @param StoreModuleRequest $request
     * @param Topic $topic
     * @return JsonResponse
     */
    public function store(StoreModuleRequest $request, Topic $topic): JsonResponse
    {
        try {
            $data = $this->service->store($request, $topic);
            $this->module->store($data);

            return ResponseHelper::success($data, message('alert.add_success'));
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * Display the specified resource.
     * 
     * @param Module $module
     * @return JsonResponse
     */
    public function show(Module $module): JsonResponse
    {
        try {
            $data = ModuleDetailResource::make($module);

            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (Throwable $th){
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param UpdateModuleRequest $request
     * @param Module $module
     * @return JsonResponse
     */
    public function update(UpdateModuleRequest $request, Module $module): JsonResponse
    {
        try {
            $data = $this->service->update($request, $module);
            $this->module->update($module->id, $data);

            return ResponseHelper::success($data, message('alert.add_success'));
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Module $module
     * @return JsonResponse
     */
    public function destroy(Module $module): JsonResponse
    {
        try {
            $this->module->delete($module->id);

            return ResponseHelper::success(null, message('alert.delete_success'));
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.delete_failed', $th));
        }
    }
}
