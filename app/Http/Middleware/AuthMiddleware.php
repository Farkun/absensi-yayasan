<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        $auth_guard = null;
        $authenticated = false;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $auth_guard = $guard;
                $authenticated = true;
            }
        }
        if (!$authenticated) {
            if ($auth_guard == 'user') return redirect()->route('loginadm');
            return redirect()->route('log');
        }
        return $next($request);
    }
}
