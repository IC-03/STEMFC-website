<?php

namespace App\Http\Middleware;

use Closure;
use Psr\Http\Message\ResponseInterface; // for type hinting (optional)

class PreventBackHistory
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Only set these headers if the response actually has a "headers" property
        if (method_exists($response, 'headers')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
        }

        return $response;
    }
}
