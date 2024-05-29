<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MyMiddleware
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

            $responseData['my-key'] = 'super secret';

            $result->setData($responseData);
        }

        return $result;
    }
}
