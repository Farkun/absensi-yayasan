<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::guard('pegawai')->attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect('/dashboard');
        } else {
            return redirect('/login')->with(['warning' => 'Username / Password Wrong']);
        }
    }

    public function loginadmin(Request $request) {
        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/adm/dashboardadmin');
        } else {
            return redirect('/adm')->with(['warning' => 'Email / Password Wrong']);
        }
    }

    public function logout(Request $request){
        if (Auth::guard('pegawai')->check()) {
            Auth::guard('pegawai')->logout();
            return redirect('/login');
        }
    }

    public function logoutadmin(Request $request) {
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
            return redirect('/adm');
        }
    }
}