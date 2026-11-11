<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if(!$request->user() || !$request->user()->hasAnyRole($roles)) {
            if($request->wantsJson()){
                return ResponseHelper::error('Unauthorized', Response::HTTP_FORBIDDEN);
            }
            return abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
