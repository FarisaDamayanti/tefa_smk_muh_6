<?php

namespace App\Services\Auth;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class LoginService
{
    /**
     * Handle a login request to the application.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws ValidationException
     */
    public function handleLogin(LoginRequest $request)
    {
        $data = $request->validated();

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = Auth::user();

            $this->createBearerTokenForWeb($user);

            return $this->redirectBasedOnRole($user);
        }


        return redirect()->back()->withErrors("Ada Yang Salah");
    }

    /**
     * Create Sanctum bearer token for web-authenticated user
     *
     * @param User $user
     * @return void
     */
    public function createBearerTokenForWeb(User $user)
    {
        if (method_exists($user, 'createToken')) {
            $user->tokens()->delete();

            $this->createSession($user);

            $token = $user->createToken('web-session-token')->plainTextToken;

            session(['api_bearer' => $token]);
        }
    }

    public function createSession(User $user)
    {
        if(session('login_session')){
            session()->forget('login_session');
        }
        $uuid = Str::uuid();

        $user->web_session = $uuid;
        $user->save();

        session(['login_session' => $uuid->toString()]);
    }

    /**
     * Redirect user based on their role
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnRole(User $user)
    {
        $role = $user->roles->pluck('name')->first() ?? '';

        return match($role) {
            'student' => to_route('student.dashboard'),
            'teacher' => to_route('teacher.dashboard'),
            'school' => to_route('school.index'),
            'staff' => to_route('employee.dashboard'),
            default => redirect()->back()->withErrors("Ada Yang Salah"),
        };
    }

    /**
     * Handle user logout
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleLogout()
    {
        $user = Auth::user();

        if ($user && method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }

        session()->forget(['api_bearer']);

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }
}
