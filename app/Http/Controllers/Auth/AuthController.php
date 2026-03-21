<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRqt;
use App\Http\Resources\Auth\LoginResource;
use App\services\Auth\AuthService;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRqt $request, AuthService $authService)
    {
        $data = $authService->login($request);

        return (new LoginResource($data))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function loginEmpleado(LoginRqt $request, AuthService $authService)
    {
        $data = $authService->loginEmpleado($request);
        return (new LoginResource($data))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function logout()
    {
        $data = $this->authService->logout();
        return response()->json($data);
    }
}
