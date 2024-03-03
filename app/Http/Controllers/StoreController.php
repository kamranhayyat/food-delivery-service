<?php

namespace App\Http\Controllers;

use App\Mail\DefaultStorePassword;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
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

        $addressPayload = $request->only(['street', 'city', 'country', 'line address 1']);
        $addressPayload['type'] = Store::STORE_ADDRESS_TYPE;

        $user = User::query()->create($userPayload);
        Mail::to($user->email)->send(new DefaultStorePassword($userPayload['password']));

        $store = $user->store()->create($request->only(['moto']));
        $storeAddresses = $store->addresses()->create($addressPayload);

        return response()->json([
            'data' => [
                'user' => $user,
                'storeAddresses' => $storeAddresses,
                'store' => $store
            ],
            'message' => 'Store registered successfully'
        ]);
    }

    public function processStoreRequest(Request $request, Store $store): JsonResponse
    {
        $request->validate([
            'storeId' => 'integer|required',
            'status' => [
                'required',
                Rule::in([Store::APPROVED_STORE, Store::DISAPPROVED_STORE]),
            ]
        ]);

        $store = Store::query()->find($request['storeId']);
        $request['status'] === Store::DISAPPROVED_STORE ? $this->disApproveStore($store) : $this->approveStore($store);
        $store->save();

        return response()->json([
            'data' => [
                'store' => $store
            ],
            'message' => 'Store processed successfully'
        ]);
    }

    private function disApproveStore(Store $store): void
    {
        $store->status = Store::DISAPPROVED_STORE;
    }

    private function approveStore(Store $store): void
    {
        $store->status = Store::APPROVED_STORE;
    }
}
