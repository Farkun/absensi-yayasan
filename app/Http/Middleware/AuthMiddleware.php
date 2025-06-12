<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
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
    public function handle(Request $request, Closure $next, $guard)
    {
        if ($guard == 'user') {
            if (Auth::guard('user')->check()) return $next($request);
            elseif (Auth::guard('pegawai')->check()) return redirect('/dashboard');
            else return redirect()->route('loginadm');
        } elseif ($guard == 'pegawai') {
            if (Auth::guard('pegawai')->check()) return $next($request);
            elseif (Auth::guard('user')->check()) return redirect('/adm/dashboardadmin');
            else return redirect()->route('log');
        }
        else return redirect()->route('log');
    }
}
