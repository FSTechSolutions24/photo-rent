<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EnsureUserIsPhotographer
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || $user->type !== 'photographer') {
            abort(403, 'Access denied. Only active photographers can access this section.');
        }

        if ($user->photographer) {
            $user->photographer->expireTrialIfNeeded();
            $user->refresh();
        }

        if(!Gate::allows('has-photographer', $user)) { //the profile is not completed yet
            if ($user->photographer) {
                return redirect()->route('dashboard.profile.inactive');
            }

            return redirect()->route('dashboard.profile.create');
        }

        return $next($request);
    }
}
