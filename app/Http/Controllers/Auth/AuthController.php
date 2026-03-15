<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = $request->validate([
            "email" => "required|email",
            "password" => "required|string"
        ]);
        $user = User::where("email", $validate["email"])->first();
        if (!$user || !Hash::check($validate["password"], $user->password)) {
            return response()->json(["message" => "Credenciales Invalidas"], 403);
        }
        $token = $user->createToken("api-token")->plainTextToken;
        return response()->json([
            "user" => $user,
            "token" => $token
        ]);
    }
}
