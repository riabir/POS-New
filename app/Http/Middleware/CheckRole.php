<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  // This will accept one or more role strings
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check if user is authenticated
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Check if the user's role is in the list of allowed roles
        $user = Auth::user();
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                // 3. If they have the role, let them proceed
                return $next($request);
            }
        }

        // 4. If they don't have any of the required roles, show an error
        abort(403, 'UNAUTHORIZED ACTION.');
    }
}