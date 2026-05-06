<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyCsrfToken extends \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'id/send-otp',
        'id/verify-otp',
        'id/sign-up',
        'id/sign-in',
        'id/forgot-password/*',
        'api/*',
        'digi/callback/haryserver',
        'tokopaycallback',
        'paydisini/callback',
        'tripay/callback',
        'callback/tripay',
        'callback/digiflazz',
        'id/harga',
        'id/konfirmasi-data',
        'id',
        'check-voucher'
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Panggil parent agar CSRF check tetap aktif (kecuali route di $except)
        $response = parent::handle($request, $next);

        $response->headers->set('Set-Cookie', 'SameSite=None; Secure');

        return $response;
    }
}
