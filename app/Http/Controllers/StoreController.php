<?php

namespace App\Http\Controllers;

use App\Mail\DefaultStorePassword;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreController extends Controller
{
    public function registerStore(Request $request): JsonResponse
    {
        $request->validate([
            'moto' => 'required|string|max:150'
        ]);

        $user = Auth::user();
        $store = $user->store()->create($request->only(['moto']));

        return response()->json([
            'data' => [
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
