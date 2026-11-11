<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use App\Services\Auth\LoginService;
use App\Services\UpdatePasswordService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected LoginService $service,
        private UpdatePasswordService $updatePasswordService
    ){}

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Reset Given User Password
     *
     * @return void
     */
    protected function resetPassword(User $user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $this->service->createBearerTokenForWeb($user);
        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    /**
     * Handle Update password for user
     *
     * @param UpdatePasswordRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse|RedirectResponse
    {
        return $this->updatePasswordService->handleUpdate($request);
    }
}
