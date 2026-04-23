<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user && in_array($user->role, ['admin', 'super_admin'])) {
                    return redirect('/admin/dashboard');
                }

                return redirect('/home');
            }
        }

        return $next($request);
    }
}