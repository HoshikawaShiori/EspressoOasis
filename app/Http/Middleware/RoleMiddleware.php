<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if ($request->user()->hasAuthorizedRole()) {
            return $next($request);
        } else{
            abort(403, 'Unauthorized.');
        }

         if ($request->user()->hasSuperAdminRole()) {
            return $next($request);
        }

         if ($request->user()->hasAdminRole()) {
            return $next($request);
        }else{
            abort(403, 'Unauthorized.');
        }

         if ($request->user()->hasAttendantRole()) {
            return $next($request);
        } else {
            abort(403, 'Unauthorized.');
        }
    }

}
