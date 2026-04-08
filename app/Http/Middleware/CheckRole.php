<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->role === "Admin") {
                return $next($request);
            }
            
            // If they are logged in but not an admin, redirect them to their dashboard
            // or just abort with 403 if it's strictly an admin route.
            // Since this middleware is used primarily for admin access:
            return redirect('/id/dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }

        return redirect('/id/sign-in');
    }
}
