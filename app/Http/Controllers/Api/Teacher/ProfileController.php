<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ProfileRequest;
use App\Services\Teacher\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct
    (
        private ProfileService $service,
    ){}

    /**
     * update teacher profile
     *
     * @param ProfileRequest $request
     * @return JsonResponse
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->service->update($request);

            DB::commit();
            return ResponseHelper::success($request->validated(), message('alert.update_success'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }

    public function deleteImage()
    {
        try {
            $is_success = $this->service->deleteImage();
            if($is_success) {
                return ResponseHelper::success(null, message('alert.delete_success'));
            } else {
                return ResponseHelper::success(null, message('alert.delete_failed', 'Gambar tidak ada'));
            }
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.delete_failed', $th));
        }
    }
}
