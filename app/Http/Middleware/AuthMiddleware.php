<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'No autenticado'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    protected function unauthorized(string $message): Response
    {
        return response()->json([
            'message' => $message
        ], Response::HTTP_UNAUTHORIZED);
    }
}
