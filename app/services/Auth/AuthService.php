<?php

namespace App\Services\Auth;

use App\Http\Requests\LoginRqt;
use App\Models\Administracion\Empleados;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthService
{
    public function login(LoginRqt $request): array
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'auth' => ['Credenciales inválidas'],
            ]);
        }

        $user = Auth::user()->load('profile');


        $user->tokens()->delete();

        $accessToken = $user->createToken('access-token')->plainTextToken;
        $refreshToken = $user->createToken('refresh-token')->plainTextToken;

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'user' => $user,
        ];
    }

    public function loginEmpleado(LoginRqt $request): array
    {

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'auth' => ['Credenciales inválidas'],
            ]);
        }


        $user = Auth::user()->load('profile');


        // validar empleado
        if (!$user->empleado) {
            Auth::logout();

            throw ValidationException::withMessages([
                'auth' => ['No tienes acceso al sistema'],
            ]);
        }

        // validar soft delete
        $empleado = Empleados::withTrashed()
            ->where('user_id', $user->id)
            ->first();

        if ($empleado && $empleado->trashed()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'auth' => ['Tu cuenta de empleado está desactivada'],
            ]);
        }

        // eliminar tokens anteriores
        $user->tokens()->delete();

        // 🔑 Access Token (para panel)
        $accessToken = $user->createToken('access-empleado')->plainTextToken;

        // 🔁 Refresh Token
        $refreshToken = $user->createToken('refresh-empleado')->plainTextToken;

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'user' => $user,
        ];
    }

    public function refresh(Request $request)
    {
        $user = $request->user();

        $token = $user->currentAccessToken();

        if ($token->name !== 'refresh-token') {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $user->tokens()->delete();

        return response()->json([
            'accessToken' => $user->createToken('access-token')->plainTextToken,
            'refreshToken' => $user->createToken('refresh-token')->plainTextToken,
        ]);
    }
}
