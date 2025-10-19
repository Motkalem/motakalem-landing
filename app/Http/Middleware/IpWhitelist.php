<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IpWhitelist
{
    public function handle(Request $request, Closure $next)
    {
        // Get allowed IPs from .env
        $whitelist = explode(',', env('ALLOWED_API_IPS', ''));

        // Always allow requests from the server itself
        $serverIps = ['127.0.0.1', '::1', $_SERVER['SERVER_ADDR'] ?? ''];

        $requestIp = $request->ip();

        if (!in_array($requestIp, $whitelist) && !in_array($requestIp, $serverIps)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
