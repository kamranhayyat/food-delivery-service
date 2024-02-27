<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

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
            return response()->json([
                'data' => [
                    'token' => $token->plainTextToken,
                    'user' => Auth::user()
                ],
                'message' => 'User logged in successfully!'
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 400);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully']);
    }

    public function register(Request $request): JsonResponse
    {
        $passwordRules = Password::min(8)->symbols();

        $request = $request->validate([
            'email' => 'email|required',
            'name' => 'required',
            'password' => ['required', 'confirmed', $passwordRules],
            'phone' => 'required|digits:11',
        ]);

        $user = User::query()->create($request);

        return response()->json([
            'data' => [
                'user' => $user
            ],
            'message' => 'User registered successfully!'
        ]);
    }
}
