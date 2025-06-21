<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogEndpointHit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $response = $next($request);
        $end = microtime(true);

        $time = $end - $start;
        $timeMs = $time * 1000;

        Log::driver('endpoint_hit')->info('Endpoint hit', [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->url(),
            'path' => $request->path(),
            'time' => $timeMs,
        ]);

        return $response;
    }
}
