<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmpleadoMiddleware
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
                'message' => 'Usuario no autenticado'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!auth()->user()->empleado) {
            return response()->json([
                'message' => 'Acceso solo para empleados'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
