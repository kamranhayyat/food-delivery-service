<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class StoreController extends Controller
{
    public function registerStore(Request $request)
    {
        $passwordRules = Password::min(8)->symbols();

        $validatedRequest = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'email|required',
            'phone' => 'required|digits:11',
            'moto' => 'required|string|max:150',
            'street' => 'required|string|max:150',
            'city' => 'required|string|max:50',
            'country' => 'required|string|max:150',
            'line address 1' => 'required|string|max:150',
        ]);

        $store = Store::query()->create($validatedRequest);
    }
}
