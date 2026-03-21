<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = $request->user();

        // Verifica si el usuario está autenticado
        if (!$user) {
            return response()->json([
                'message' => 'No autenticado.',
            ], 401);
        }

        // ✅ Permitir acceso total al superadmin
        if ($user->hasAnyRole(['superadmin', 'programador'])) {
            return $next($request);
        }

        // ✅ Verifica permiso específico
        if (!$user->can($permission)) {
            return response()->json([
                'message' => "No tienes permiso para: {$permission}",
            ], 403);
        }

        // Si tiene el permiso, continúa
        return $next($request);
    }
}
