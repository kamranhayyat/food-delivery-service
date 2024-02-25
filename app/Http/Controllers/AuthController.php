<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)) {
            $token = Auth::user()->createToken(Auth::user()->name);
            return response()->json(['data' => ['token' => $token->plainTextToken]]);
        }

        return response()->json(['data' => ['error' => 'Unauthorized']], 401);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json(['data' => ['message' => 'User logged out successfully']]);
    }
}
