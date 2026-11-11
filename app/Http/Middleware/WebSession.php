<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if($user->web_session !== session('login_session')){
            session()->forget('api_bearer');
            auth()->logout();
            $user->currentAccessToken()?->delete();
            return redirect()->route('login')->with('error', 'Sesi login sudah tidak valid.');
        }
        return $next($request);
    }
}
