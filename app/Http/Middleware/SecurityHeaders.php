<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
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
        $response = $next($request);

        // Security headers
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        $csp = "default-src 'self'; "
             . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://www.google.com https://www.gstatic.com https://unpkg.com https://www.youtube.com https://s.ytimg.com https://cdn.tailwindcss.com https://cdn.datatables.net; "
             . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https://cdn.datatables.net; "
             . "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; "
             . "img-src 'self' data: https:; "
             . "connect-src 'self' https://ipinfo.io https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com https://www.youtube.com https://s.ytimg.com https://identitytoolkit.googleapis.com https://securetoken.googleapis.com https://firebaseinstallations.googleapis.com https://www.googleapis.com https://*.firebaseio.com https://*.firebaseapp.com; "
             . "frame-src 'self' https://www.google.com https://www.youtube.com https://*.firebaseapp.com;" ;
             
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
