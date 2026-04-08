<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\WhitelistedIP;

class WhitelistIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $whitelistedIps = WhitelistedIP::pluck('ip_address')->toArray();

        if (!in_array($request->ip(), $whitelistedIps)) {
            return response()->json(['error' => 'Access denied from your IP: ' . $request->ip()], 403);
        }

        return $next($request);
    }
}
