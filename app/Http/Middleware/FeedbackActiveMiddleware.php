<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class FeedbackActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $permission = Permission::where('name', 'active_feedback')
            ->where('guard_name', 'feedback')
            ->first();

        if (!$permission) {
            abort(403, 'Fitur feedback sedang dinonaktifkan');
        }

        return $next($request);
    }
}
