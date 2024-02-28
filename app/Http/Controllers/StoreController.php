<?php

namespace App\Http\Controllers;

use App\Mail\DefaultStorePassword;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class StoreController extends Controller
{
    public function registerStore(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'email|required',
            'phone' => 'required|digits:11',
            'moto' => 'required|string|max:150',
            'street' => 'required|string|max:150',
            'city' => 'required|string|max:50',
            'country' => 'required|string|max:150',
            'line address 1' => 'required|string|max:150',
        ]);

        $userPayload = $request->only(['name', 'email', 'phone']);
        $userPayload['password'] = Hash::make('default123@');

        $user = User::query()->create($userPayload);
        $store = $user->store()->create($request->only(['name', 'moto']));
        Mail::to($user->email)->send(new DefaultStorePassword($userPayload['password']));

        return response()->json([
            'data' => [
                'user' => $user,
                'store' => $store
            ],
            'message' => 'Store registered successfully'
        ]);
    }
}
