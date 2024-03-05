<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function createAddress(Request $request): JsonResponse
    {
        $validateRequest = $request->validate([
            'street' => 'required|string|max:150',
            'city' => 'required|string|max:50',
            'country' => 'required|string|max:150',
            'line address 1' => 'required|string|max:150',
        ]);

        $user = Auth::user();

        $address = $user->addresses()->create($validateRequest);

        return response()->json([
            'data' => [
                'address' => $address
            ],
            'message' => 'Address created successfully'
        ]);
    }
}
