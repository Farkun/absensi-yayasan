<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        if (! $request->expectsJson()) {
            if ($guards[0] == 'user') return route('loginadm');
            return route('log');
        }
        return null;
    }
}
