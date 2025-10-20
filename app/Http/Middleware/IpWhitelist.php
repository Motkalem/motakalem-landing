<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IpWhitelist
{
    public function handle(Request $request, Closure $next)
    {
        // Allowed domains
        $allowedDomains = [
            'https://smotkalem.sa',
            'http://smotkalem.sa',
            'https://www.smotkalem.sa',
            'http://www.smotkalem.sa',
        ];

        // Get the request Origin or Referer
        $origin = $request->headers->get('origin') ?? $request->headers->get('referer');

        // If no Origin/Referer header or it's not from allowed domains, block it
        if (!$origin || !$this->isAllowedDomain($origin, $allowedDomains)) {
            return response()->json(['message' => 'Unauthorized request source'], 403);
        }

        return $next($request);
    }

    private function isAllowedDomain($origin, $allowedDomains)
    {
        foreach ($allowedDomains as $domain) {
            if (stripos($origin, $domain) === 0) {
                return true;
            }
        }
        return false;
    }
}
