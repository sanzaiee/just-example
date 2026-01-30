<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        // dd([
        //     'auth_check' => Auth::check(),
        //     'user' => Auth::user(),
        //     'status' => Auth::check() ? Auth::user()->status : null,
        //     'status_type' => Auth::check() ? gettype(Auth::user()->status) : null,
        // ]);

        if (Auth::check() && Auth::user()->status == false) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Your account has been disabled.');
        }

        return $next($request);
    }
}
