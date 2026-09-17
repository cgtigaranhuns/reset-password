<?php

// app/Http/Middleware/RestrictToCorporateNetwork.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class RestrictToCorporateNetwork
{
    public function handle(Request $request, Closure $next)
    {
        $allowedRanges = config('services.corporate_network.ranges', []);
        // ex no config/services.php: 'corporate_network' => ['ranges' => explode(',', env('CORPORATE_IP_RANGES'))]
        // .env: CORPORATE_IP_RANGES=10.0.0.0/8,192.168.10.0/24

        if (!IpUtils::checkIp($request->ip(), $allowedRanges)) {
            abort(403, 'Este recurso só está disponível dentro da rede corporativa.');
        }

        return $next($request);
    }
}