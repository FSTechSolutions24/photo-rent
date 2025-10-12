<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsPhotographer
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || $user->type !== 'photographer') {
            abort(403, 'Access denied. Only photographers can access this section.');
        }

        return $next($request);
    }
}
