<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepository;
use App\Helpers\ResponseHelper;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UpdatePasswordService
{
    public function __construct(
        private UserRepository $user,
    ){}

    /**
     * Handle user password change
     *
     * @param UpdatePasswordRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function handleUpdate(UpdatePasswordRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        try {
            $user = auth_user();

            $this->user->update($user->id, ['password' => $user->password = bcrypt($data['password'])]);

            return $this->handleResponse($request);
        } catch (Throwable $th) {
            return $this->handleFailedResponse($request, $th);
        }
    }

    /**
     * Handle successful response
     *
     * @param UpdatePasswordRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function handleResponse(UpdatePasswordRequest $request): JsonResponse|RedirectResponse
    {
        if($request->wantsJson()){
            return ResponseHelper::success(auth_user(), message('alert.update_success'));
        }

        return back()->with('success', 'Berhasil mengupdate password');
    }

    /**
     * Handle failed response
     *
     * @param UpdatePasswordRequest $request
     * @param Throwable $th
     * @return JsonResponse|RedirectResponse
     */
    public function handleFailedResponse(UpdatePasswordRequest $request, Throwable $th): JsonResponse|RedirectResponse
    {
        if($request->wantsJson()){
            return ResponseHelper::error(message('alert.update_failed', $th));
        }

        return back()->with('error', 'Gagal update password. ' . $th->getMessage());
    }



}
