<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DebugMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $result = $next($request);

        if ($result instanceof JsonResponse) {
            $responseData = $result->getData(true);
            $responseData['debug-info']['requested-get-parameters'] = $request->query();
            $responseData['debug-info']['requested-post-body'] = $request->post();
            if (!defined('LARAVEL_START')) {
                define('LARAVEL_START', microtime(true));
            }
            $responseData['debug-info']['execution-time-milliseconds'] = (microtime(true) - LARAVEL_START) * 1000;
            $result->setData($responseData);
        }
        return $result;
    }
}
