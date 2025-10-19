<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IpWhitelist
{
    public function handle(Request $request, Closure $next)
    {
        // Get allowed IPs from .env and convert to array
        $whitelist = explode(',', env('ALLOWED_API_IPS', ''));

        // Check if the request IP is in the whitelist
        if (!in_array($request->ip(), $whitelist)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
