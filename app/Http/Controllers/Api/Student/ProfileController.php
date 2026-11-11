<?php

namespace App\Http\Controllers\Api\Student;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Services\Student\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $service
    ) {}

    /**
     * Update student profile
     *
     * @param ProfileRequest $request
     * @return JsonResponse
     */
    public function update(ProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->service->update($request);
            DB::commit();
            return ResponseHelper::success(message('alert.update_success'));
            } catch(Throwable $th){
            DB::rollBack();
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }

    /**
     * remove image student logged in
     *
     * @return JsonResponse
     */
    public function deleteImage(): JsonResponse
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
