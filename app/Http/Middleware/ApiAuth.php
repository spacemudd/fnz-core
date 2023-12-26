<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // check for header
        if (!$request->headers->has('X-FNZ-KEY')) {
            return response()->json(['error' => 'Authorization header missing'], 401);
        }

        if ($request->headers->get('X-FNZ-KEY') != env('X_FNZ_API_KEY')) {
            return response()->json(['error' => 'Authorization cred is wrong'], 401);
        }

        return $next($request);
    }
}
